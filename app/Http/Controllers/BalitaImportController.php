<?php

namespace App\Http\Controllers;

use App\Models\Balita;
use App\Models\Posyandu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;

class BalitaImportController extends Controller
{
    /**
     * Download template Excel kosong.
     */
    public function template()
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template Import Balita');

        // Header
        $headers = [
            'A1' => 'nama',
            'B1' => 'tanggal_lahir',
            'C1' => 'jenis_kelamin',
            'D1' => 'nama_orang_tua',
            'E1' => 'no_telepon',
            'F1' => 'alamat',
            'G1' => 'kode',
        ];
        foreach ($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }

        // Style header
        $headerStyle = [
            'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
            'fill'      => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => ['argb' => 'FF0070C0']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            'borders'   => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
        ];
        $sheet->getStyle('A1:G1')->applyFromArray($headerStyle);

        // Contoh baris data
        $examples = [
            ['Ahmad Fauzi', '2022-03-15', 'L', 'Budi Santoso', '081234567890', 'Jl. Mawar No.1', 'POS001'],
            ['Siti Aisyah',  '2021-07-22', 'P', 'Rina Dewi',    '082345678901', 'Jl. Melati No.5', 'POS002'],
        ];
        foreach ($examples as $row => $data) {
            $sheet->fromArray($data, null, 'A' . ($row + 2));
        }

        // Auto width
        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Sheet petunjuk
        $guide = $spreadsheet->createSheet();
        $guide->setTitle('Petunjuk');
        $guide->setCellValue('A1', 'Petunjuk Pengisian');
        $guide->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $guideRows = [
            ['Kolom',         'Keterangan',                           'Contoh',         'Wajib'],
            ['nama',          'Nama lengkap balita',                  'Ahmad Fauzi',    'Ya'],
            ['tanggal_lahir', 'Format YYYY-MM-DD',                    '2022-03-15',     'Ya'],
            ['jenis_kelamin', 'L = Laki-laki, P = Perempuan',         'L',              'Ya'],
            ['nama_orang_tua','Nama ayah / ibu / wali',               'Budi Santoso',   'Ya'],
            ['no_telepon',    'Nomor HP (boleh kosong)',               '081234567890',   'Tidak'],
            ['alamat',        'Alamat lengkap (boleh kosong)',         'Jl. Mawar No.1', 'Tidak'],
            ['kode', 'Kode posyandu tempat balita terdaftar','POS001',         'Ya'],
        ];
        foreach ($guideRows as $r => $row) {
            $guide->fromArray($row, null, 'A' . ($r + 3));
        }
        $guide->getStyle('A3:D3')->getFont()->setBold(true);
        foreach (range('A', 'D') as $col) {
            $guide->getColumnDimension($col)->setAutoSize(true);
        }

        $spreadsheet->setActiveSheetIndex(0);

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'template_import_balita.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }

    /**
     * Preview data dari file Excel sebelum disimpan.
     * Kembalikan JSON: { rows: [...], errors: [...] }
     */
    public function preview(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:5120',
        ]);

        try {
            $spreadsheet = IOFactory::load($request->file('file')->getPathname());
            $sheet       = $spreadsheet->getActiveSheet();
            $rows        = $sheet->toArray(null, true, true, true); // associative by column letter
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal membaca file: ' . $e->getMessage()], 422);
        }

        // Baris 1 = header, mulai dari baris 2
        $posyanduMap = Posyandu::pluck('id', 'kode')->toArray(); // ['POS001' => 1, ...]

        $previewRows = [];
        $errors      = [];

        foreach ($rows as $rowIndex => $row) {
            if ($rowIndex === 1) continue; // skip header
            if (empty(array_filter($row)))  continue; // skip baris kosong

            $data = [
                'no'              => $rowIndex - 1,
                'nama'            => trim($row['A'] ?? ''),
                'tanggal_lahir'   => trim($row['B'] ?? ''),
                'jenis_kelamin'   => strtoupper(trim($row['C'] ?? '')),
                'nama_orang_tua'  => trim($row['D'] ?? ''),
                'no_telepon'      => trim($row['E'] ?? ''),
                'alamat'          => trim($row['F'] ?? ''),
                'kode'   => trim($row['G'] ?? ''),
            ];

            // Validasi per baris
            $rowErrors = $this->validateRow($data, $posyanduMap, $rowIndex);

            // Resolusi posyandu
            $data['nama_posyandu'] = isset($posyanduMap[$data['kode']])
                ? Posyandu::find($posyanduMap[$data['kode']])->nama
                : '— tidak ditemukan —';

            $data['status'] = empty($rowErrors) ? 'valid' : 'error';

            $previewRows[] = $data;
            if (!empty($rowErrors)) {
                $errors[] = ['baris' => $rowIndex, 'pesan' => $rowErrors];
            }
        }

        return response()->json([
            'rows'        => $previewRows,
            'total'       => count($previewRows),
            'valid'       => collect($previewRows)->where('status', 'valid')->count(),
            'error_count' => count($errors),
            'errors'      => $errors,
        ]);
    }

    /**
     * Simpan data yang sudah dipreview dan divalidasi.
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:5120',
        ]);

        try {
            $spreadsheet = IOFactory::load($request->file('file')->getPathname());
            $sheet       = $spreadsheet->getActiveSheet();
            $rows        = $sheet->toArray(null, true, true, true);
        } catch (\Exception $e) {
            return back()->withErrors(['file' => 'Gagal membaca file: ' . $e->getMessage()]);
        }

        $posyanduMap = Posyandu::pluck('id', 'kode')->toArray();

        $imported  = 0;
        $skipped   = 0;
        $rowErrors = [];

        DB::beginTransaction();
        try {
            foreach ($rows as $rowIndex => $row) {
                if ($rowIndex === 1) continue;
                if (empty(array_filter($row))) continue;

                $data = [
                    'nama'           => trim($row['A'] ?? ''),
                    'tanggal_lahir'  => trim($row['B'] ?? ''),
                    'jenis_kelamin'  => strtoupper(trim($row['C'] ?? '')),
                    'nama_orang_tua' => trim($row['D'] ?? ''),
                    'no_telepon'     => trim($row['E'] ?? ''),
                    'alamat'         => trim($row['F'] ?? ''),
                    'kode'  => trim($row['G'] ?? ''),
                ];

                $errors = $this->validateRow($data, $posyanduMap, $rowIndex);
                if (!empty($errors)) {
                    $rowErrors[] = ['baris' => $rowIndex, 'pesan' => $errors];
                    $skipped++;
                    continue;
                }

                Balita::create([
                    'kode_balita'    => $this->generateKode(),
                    'nama'           => $data['nama'],
                    'tanggal_lahir'  => $data['tanggal_lahir'],
                    'jenis_kelamin'  => $data['jenis_kelamin'],
                    'nama_orang_tua' => $data['nama_orang_tua'],
                    'no_telepon'     => $data['no_telepon'] ?: null,
                    'alamat'         => $data['alamat'] ?: null,
                    'posyandu_id'    => $posyanduMap[$data['kode']],
                ]);
                $imported++;
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['file' => 'Terjadi kesalahan saat menyimpan: ' . $e->getMessage()]);
        }

        $message = "Import selesai: {$imported} data berhasil disimpan.";
        if ($skipped > 0) {
            $message .= " {$skipped} baris dilewati karena ada error.";
            session()->flash('import_errors', $rowErrors);
        }

        return redirect()->route('balita.index')->with('success', $message);
    }

    // ── Private helpers ──────────────────────────────────────────

    private function validateRow(array $data, array $posyanduMap, int $rowIndex): array
    {
        $errors = [];

        if (empty($data['nama'])) {
            $errors[] = 'Nama wajib diisi';
        }

        if (empty($data['tanggal_lahir'])) {
            $errors[] = 'Tanggal lahir wajib diisi';
        } elseif (!\DateTime::createFromFormat('Y-m-d', $data['tanggal_lahir'])) {
            $errors[] = 'Format tanggal lahir harus YYYY-MM-DD (contoh: 2022-03-15)';
        }

        if (!in_array($data['jenis_kelamin'], ['L', 'P'])) {
            $errors[] = 'Jenis kelamin harus L atau P';
        }

        if (empty($data['nama_orang_tua'])) {
            $errors[] = 'Nama orang tua wajib diisi';
        }

        if (empty($data['kode'])) {
            $errors[] = 'Kode posyandu wajib diisi';
        } elseif (!isset($posyanduMap[$data['kode']])) {
            $errors[] = "Kode posyandu '{$data['kode']}' tidak ditemukan";
        }

        return $errors;
    }

    private function generateKode(): string
    {
        do {
            $kode = 'BLT-' . strtoupper(Str::random(6));
        } while (Balita::where('kode_balita', $kode)->exists());

        return $kode;
    }
}