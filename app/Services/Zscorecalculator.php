<?php

namespace App\Services;

/**
 * ZscoreCalculator
 *
 * Menghitung z-score antropometri balita menggunakan metode LMS
 * sesuai standar WHO Child Growth Standards 2006.
 *
 * Rumus LMS (Box-Cox):
 *   z = [(X / M)^L - 1] / (L × S)
 *
 * Referensi:
 *   WHO (2006). Child Growth Standards: Methods and Development.
 *   https://www.who.int/tools/child-growth-standards
 */
class ZscoreCalculator
{
    /**
     * Mapping indikator → file CSV (relatif dari storage/app/who/)
     * Sesuaikan path jika file CSV kamu disimpan di tempat lain.
     */
    private array $csvMap = [
        'wfa'  => ['boys' => 'wfa_boys_zscores.csv',  'girls' => 'wfa_girls_zscores.csv'],
        'lhfa' => ['boys' => 'lhfa_boys_zscores.csv', 'girls' => 'lhfa_girls_zscores.csv'],
        'bmi'  => ['boys' => 'bmi_boys_zscores.csv',  'girls' => 'bmi_girls_zscores.csv'],
        'wfl'  => ['boys' => 'wfl_boys_zscores.csv',  'girls' => 'wfl_girls_zscores.csv'],
    ];

    /** Cache agar CSV tidak dibaca ulang tiap pemanggilan */
    private array $cache = [];

    // ──────────────────────────────────────────────────────────────
    // PUBLIC API
    // ──────────────────────────────────────────────────────────────

    /**
     * Hitung semua z-score sekaligus.
     *
     * @param  float  $beratBadan   kg
     * @param  float  $tinggiBadan  cm
     * @param  int    $umurBulan    0–60
     * @param  string $jenisKelamin 'L' atau 'P'
     * @return array{
     *   zscore_bbu: float|null,
     *   zscore_tbu: float|null,
     *   zscore_bbtb: float|null,
     *   kategori_bbu: string,
     *   kategori_tbu: string,
     *   kategori_bbtb: string,
     * }
     */
    public function hitungSemua(
        float $beratBadan,
        float $tinggiBadan,
        int   $umurBulan,
        string $jenisKelamin
    ): array {
        $sex = strtoupper($jenisKelamin) === 'L' ? 'boys' : 'girls';

        $zBbu  = $this->hitungZscore('wfa',  $sex, $umurBulan,   $beratBadan);
        $zTbu  = $this->hitungZscore('lhfa', $sex, $umurBulan,   $tinggiBadan);
        $zBbtb = $this->hitungZscore('wfl',  $sex, $tinggiBadan, $beratBadan);

        return [
            'zscore_bbu'   => $zBbu,
            'zscore_tbu'   => $zTbu,
            'zscore_bbtb'  => $zBbtb,
            'kategori_bbu'  => $this->kategoriBbu($zBbu),
            'kategori_tbu'  => $this->kategoriTbu($zTbu),
            'kategori_bbtb' => $this->kategoriBbtb($zBbtb),
        ];
    }

    // ──────────────────────────────────────────────────────────────
    // CORE CALCULATION
    // ──────────────────────────────────────────────────────────────

    /**
     * Hitung z-score tunggal menggunakan rumus LMS WHO.
     *
     * Untuk WFL (BB/TB), $index adalah tinggi badan (bukan umur).
     * Untuk indikator lain, $index adalah umur dalam bulan.
     *
     * Jika $index tidak tepat ada di tabel (nilai desimal),
     * dilakukan interpolasi linear antara dua baris terdekat.
     *
     * @param  string $indikator  'wfa' | 'lhfa' | 'bmi' | 'wfl'
     * @param  string $sex        'boys' | 'girls'
     * @param  float  $index      umur (bulan) atau tinggi (cm)
     * @param  float  $measurement nilai yang diukur
     * @return float|null
     */
    public function hitungZscore(
        string $indikator,
        string $sex,
        float  $index,
        float  $measurement
    ): ?float {
        $tabel = $this->loadCsv($indikator, $sex);
        if (empty($tabel)) {
            return null;
        }

        // Cari dua baris yang mengapit $index untuk interpolasi
        [$bawah, $atas] = $this->cariBaris($tabel, $index);

        if ($bawah === null) {
            return null;
        }

        // Jika tepat, tidak perlu interpolasi
        if ($atas === null || $bawah['index'] === $index) {
            return $this->lmsFormula($measurement, $bawah['L'], $bawah['M'], $bawah['S']);
        }

        // Interpolasi linear L, M, S
        $frac = ($index - $bawah['index']) / ($atas['index'] - $bawah['index']);
        $L    = $bawah['L'] + $frac * ($atas['L'] - $bawah['L']);
        $M    = $bawah['M'] + $frac * ($atas['M'] - $bawah['M']);
        $S    = $bawah['S'] + $frac * ($atas['S'] - $bawah['S']);

        return $this->lmsFormula($measurement, $L, $M, $S);
    }

    /**
     * Rumus LMS WHO (Box-Cox transformation):
     *   z = [(X/M)^L - 1] / (L × S)
     *
     * Untuk z < -3 atau z > 3, WHO merekomendasikan koreksi:
     *   z_adj = z ± (|z| - 3) × (SD3 - SD2) / SD_distance
     * Namun dalam praktik klinis, cukup cap di ±6 SD.
     */
    private function lmsFormula(float $X, float $L, float $M, float $S): float
    {
        if ($M <= 0 || $S <= 0) {
            return 0.0;
        }

        if (abs($L) < 1e-6) {
            // Kasus L ≈ 0: gunakan log-normal
            $z = log($X / $M) / $S;
        } else {
            $z = (pow($X / $M, $L) - 1) / ($L * $S);
        }

        // Cap ekstrem (>6 SD biasanya data entry error)
        return round(max(-6.0, min(6.0, $z)), 2);
    }

    // ──────────────────────────────────────────────────────────────
    // KATEGORI STATUS GIZI (Permenkes No. 2 Tahun 2020)
    // ──────────────────────────────────────────────────────────────

    /**
     * BB/U — Berat Badan menurut Umur
     */
    public function kategoriBbu(?float $z): string
    {
        if ($z === null) return '-';
        if ($z < -3)          return 'Berat Badan Sangat Kurang';
        if ($z < -2)          return 'Berat Badan Kurang';
        if ($z <= 1)          return 'Berat Badan Normal';
        return                        'Risiko Berat Badan Lebih';
    }

    /**
     * TB/U — Tinggi Badan menurut Umur
     */
    public function kategoriTbu(?float $z): string
    {
        if ($z === null) return '-';
        if ($z < -3)     return 'Sangat Pendek (Severely Stunted)';
        if ($z < -2)     return 'Pendek (Stunted)';
        if ($z <= 3)     return 'Normal';
        return                  'Tinggi';
    }

    /**
     * BB/TB — Berat Badan menurut Tinggi Badan
     */
    public function kategoriBbtb(?float $z): string
    {
        if ($z === null) return '-';
        if ($z < -3)     return 'Gizi Buruk';
        if ($z < -2)     return 'Gizi Kurang';
        if ($z <= 1)     return 'Gizi Baik';
        if ($z <= 2)     return 'Berisiko Gizi Lebih';
        if ($z <= 3)     return 'Gizi Lebih';
        return                  'Obesitas';
    }

    // ──────────────────────────────────────────────────────────────
    // CSV LOADER
    // ──────────────────────────────────────────────────────────────

    /**
     * Muat CSV WHO ke array, gunakan cache agar tidak IO ulang.
     *
     * CSV disimpan di: storage/app/who/{filename}
     * Kolom wajib: Month (atau Length/Height), L, M, S
     */
    private function loadCsv(string $indikator, string $sex): array
    {
        $key = "{$indikator}_{$sex}";

        if (isset($this->cache[$key])) {
            return $this->cache[$key];
        }

        $filename = $this->csvMap[$indikator][$sex] ?? null;
        if (!$filename) {
            return $this->cache[$key] = [];
        }

        // Simpan CSV di storage/app/who/
        $path = storage_path("app/who/{$filename}");

        if (!file_exists($path)) {
            \Log::warning("WHO CSV tidak ditemukan: {$path}");
            return $this->cache[$key] = [];
        }

        $rows   = [];
        $handle = fopen($path, 'r');
        $header = null;

        while (($line = fgetcsv($handle)) !== false) {
            if ($header === null) {
                // Normalisasi header: lowercase, hapus BOM
                $header = array_map(fn($h) => strtolower(trim($h, " \t\n\r\0\x0B\xEF\xBB\xBF")), $line);
                continue;
            }

            $row = array_combine($header, $line);

            // Kolom index: 'month' untuk wfa/lhfa/bmi, 'length' atau 'height' untuk wfl
            $indexCol = $this->detectIndexColumn($header);
            if (!$indexCol || !isset($row[$indexCol])) continue;

            $rows[] = [
                'index' => (float) $row[$indexCol],
                'L'     => (float) $row['l'],
                'M'     => (float) $row['m'],
                'S'     => (float) $row['s'],
            ];
        }

        fclose($handle);

        // Pastikan urut ascending berdasarkan index
        usort($rows, fn($a, $b) => $a['index'] <=> $b['index']);

        return $this->cache[$key] = $rows;
    }

    /**
     * Deteksi kolom index dari header CSV.
     * Bisa 'month', 'length', 'height', atau variasi lainnya.
     */
    private function detectIndexColumn(array $header): ?string
    {
        $candidates = ['month', 'length', 'height', 'age', 'lengthheight'];
        foreach ($candidates as $col) {
            if (in_array($col, $header)) {
                return $col;
            }
        }
        // Fallback: kolom pertama
        return $header[0] ?? null;
    }

    /**
     * Cari dua baris yang mengapit $index.
     * Return [$bawah, $atas] — jika tepat ada, $atas = null.
     */
    private function cariBaris(array $tabel, float $index): array
    {
        $bawah = null;
        $atas  = null;

        foreach ($tabel as $row) {
            if ($row['index'] == $index) {
                return [$row, null];
            }
            if ($row['index'] < $index) {
                $bawah = $row;
            }
            if ($row['index'] > $index && $atas === null) {
                $atas = $row;
                break;
            }
        }

        return [$bawah, $atas];
    }
}