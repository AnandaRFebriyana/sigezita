<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hasil Pengukuran - {{ $pengukuran->balita->nama_balita }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Nunito', sans-serif; font-size: 12px; color: #2d3748; }
        .header { border-bottom: 3px solid #4e73df; margin-bottom: 1.5rem; padding-bottom: 1rem; }
        .logo-text { font-size: 1.5rem; font-weight: 900; color: #4e73df; }
        .logo-sub { font-size: 0.75rem; color: #718096; }
        .status-box { border-radius: 0.5rem; padding: 1rem; margin: 1rem 0; }
        .status-normal   { background: #d4edda; border: 2px solid #28a745; color: #155724; }
        .status-berisiko { background: #fff3cd; border: 2px solid #ffc107; color: #856404; }
        .status-stunting { background: #f8d7da; border: 2px solid #dc3545; color: #721c24; }
        .section-title { font-weight: 800; color: #4e73df; font-size: 0.85rem; border-bottom: 1px solid #e3e6f0; padding-bottom: 0.3rem; margin-bottom: 0.75rem; text-transform: uppercase; letter-spacing: 0.05rem; }
        .indicator-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem; }
        .badge-normal   { background: #28a745; color: #fff; padding: 2px 8px; border-radius: 20px; font-size: 0.75rem; }
        .badge-warning  { background: #ffc107; color: #fff; padding: 2px 8px; border-radius: 20px; font-size: 0.75rem; }
        .badge-danger   { background: #dc3545; color: #fff; padding: 2px 8px; border-radius: 20px; font-size: 0.75rem; }
        .badge-info     { background: #17a2b8; color: #fff; padding: 2px 8px; border-radius: 20px; font-size: 0.75rem; }
        .footer { border-top: 1px solid #e3e6f0; margin-top: 2rem; padding-top: 1rem; font-size: 0.7rem; color: #718096; }
        @media print {
            .no-print { display: none; }
            body { padding: 0; }
        }
    </style>
</head>
<body class="p-4">
    <!-- No Print Buttons -->
    <div class="no-print mb-3">
        <button onclick="window.print()" class="btn btn-primary btn-sm">
            <i class="fas fa-print mr-1"></i> Cetak
        </button>
        <a href="{{ route('pengukuran.show', $pengukuran) }}" class="btn btn-secondary btn-sm ml-2">Kembali</a>
    </div>

    <!-- Header -->
    <div class="header d-flex justify-content-between align-items-start">
        <div>
            <div class="logo-text">🍼 SiGizi Balita</div>
            <div class="logo-sub">Sistem Identifikasi Status Gizi Balita</div>
        </div>
        <div class="text-right">
            <div style="font-weight:700">HASIL PENGUKURAN ANTROPOMETRI</div>
            <div>No: PGK-{{ str_pad($pengukuran->id, 6, '0', STR_PAD_LEFT) }}</div>
            <div>Tanggal: {{ $pengukuran->tanggal_ukur->format('d F Y') }}</div>
            <div>Posyandu: {{ $pengukuran->balita->posyandu->nama_posyandu ?? '-' }}</div>
        </div>
    </div>

    <div class="row">
        <!-- Identitas -->
        <div class="col-6">
            <div class="section-title">Data Identitas Balita</div>
            <table style="width:100%;font-size:12px">
                <tr><td style="width:40%;color:#718096">Nama Balita</td><td><strong>{{ $pengukuran->balita->nama_balita }}</strong></td></tr>
                <tr><td style="color:#718096">Kode Balita</td><td>{{ $pengukuran->balita->kode_balita }}</td></tr>
                <tr><td style="color:#718096">Jenis Kelamin</td><td>{{ $pengukuran->balita->jenis_kelamin_label }}</td></tr>
                <tr><td style="color:#718096">Tanggal Lahir</td><td>{{ $pengukuran->balita->tanggal_lahir->format('d F Y') }}</td></tr>
                <tr><td style="color:#718096">Umur saat Ukur</td><td><strong>{{ $pengukuran->umur_bulan }} bulan</strong></td></tr>
                <tr><td style="color:#718096">Nama Orang Tua</td><td>{{ $pengukuran->balita->nama_orang_tua }}</td></tr>
                <tr><td style="color:#718096">Alamat</td><td>{{ $pengukuran->balita->alamat ?? '-' }}</td></tr>
            </table>
        </div>

        <!-- Data Ukur -->
        <div class="col-6">
            <div class="section-title">Data Antropometri</div>
            <table style="width:100%;font-size:12px">
                <tr><td style="width:50%;color:#718096">Berat Badan</td><td><strong>{{ $pengukuran->berat_badan }} kg</strong></td></tr>
                <tr><td style="color:#718096">Tinggi/Panjang Badan</td><td><strong>{{ $pengukuran->tinggi_badan }} cm</strong></td></tr>
                <tr><td style="color:#718096">Tanggal Pengukuran</td><td>{{ $pengukuran->tanggal_ukur->format('d/m/Y') }}</td></tr>
                <tr><td style="color:#718096">Petugas Pengukur</td><td>{{ $pengukuran->petugas->name ?? '-' }}</td></tr>
            </table>
        </div>
    </div>

    <!-- Status -->
    @php
    $sClass = match($pengukuran->status_stunting) { 'Stunting'=>'status-stunting','Berisiko Gangguan Pertumbuhan'=>'status-berisiko',default=>'status-normal' };
    @endphp
    <div class="status-box {{ $sClass }} text-center mt-3">
        <div style="font-size:1.2rem;font-weight:900">STATUS GIZI: {{ strtoupper($pengukuran->status_stunting) }}</div>
        @if($pengukuran->status_stunting === 'Stunting')
        <div style="font-size:0.85rem;margin-top:0.25rem">TB/U menunjukkan {{ $pengukuran->kategori_tbu }} — Segera konsultasikan ke tenaga kesehatan</div>
        @elseif($pengukuran->status_stunting === 'Berisiko Gangguan Pertumbuhan')
        <div style="font-size:0.85rem;margin-top:0.25rem">Perlu pemantauan gizi lebih lanjut dan intervensi nutrisi</div>
        @else
        <div style="font-size:0.85rem;margin-top:0.25rem">Pertumbuhan dalam batas normal — Pertahankan pola makan bergizi</div>
        @endif
    </div>

    <!-- Indikator Detail -->
    <div class="row mt-3">
        <div class="col-12">
            <div class="section-title">Hasil Klasifikasi Indikator</div>
            <table class="table table-bordered table-sm" style="font-size:11px">
                <thead style="background:#f8f9fc"><tr><th>Indikator</th><th>Z-Score</th><th>Kategori</th><th>Keterangan</th></tr></thead>
                <tbody>
                    <tr>
                        <td><strong>BB/U</strong><br><small>(Berat Badan / Umur)</small></td>
                        <td>{{ $pengukuran->zscore_bbu ?? '-' }} SD</td>
                        <td>{{ $pengukuran->kategori_bbu ?? '-' }}</td>
                        <td>
                            @switch($pengukuran->kategori_bbu)
                                @case('Gizi Buruk') Z-Score &lt; -3 SD @break
                                @case('Gizi Kurang') -3 SD ≤ Z &lt; -2 SD @break
                                @case('Gizi Baik') -2 SD ≤ Z ≤ +1 SD @break
                                @case('Berisiko Gizi Lebih') +1 SD &lt; Z ≤ +2 SD @break
                                @case('Gizi Lebih') +2 SD &lt; Z ≤ +3 SD @break
                                @case('Obesitas') Z &gt; +3 SD @break
                                @default - @endswitch
                        </td>
                    </tr>
                    <tr>
                        <td><strong>TB/U</strong><br><small>(Tinggi Badan / Umur)</small></td>
                        <td>{{ $pengukuran->zscore_tbu ?? '-' }} SD</td>
                        <td>{{ $pengukuran->kategori_tbu ?? '-' }}</td>
                        <td>
                            @switch($pengukuran->kategori_tbu)
                                @case('Sangat Pendek') Z-Score &lt; -3 SD @break
                                @case('Pendek') -3 SD ≤ Z &lt; -2 SD @break
                                @case('Normal') -2 SD ≤ Z ≤ +3 SD @break
                                @case('Tinggi') Z &gt; +3 SD @break
                                @default - @endswitch
                        </td>
                    </tr>
                    <tr>
                        <td><strong>BB/TB</strong><br><small>(Berat Badan / Tinggi Badan)</small></td>
                        <td>{{ $pengukuran->zscore_bbtb ?? '-' }} SD</td>
                        <td>{{ $pengukuran->kategori_bbtb ?? '-' }}</td>
                        <td>
                            @switch($pengukuran->kategori_bbtb)
                                @case('Sangat Kurus') Z-Score &lt; -3 SD @break
                                @case('Kurus') -3 SD ≤ Z &lt; -2 SD @break
                                @case('Normal') -2 SD ≤ Z ≤ +1 SD @break
                                @case('Berisiko Gemuk') +1 SD &lt; Z ≤ +2 SD @break
                                @case('Gemuk') +2 SD &lt; Z ≤ +3 SD @break
                                @case('Obesitas') Z &gt; +3 SD @break
                                @default - @endswitch
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tanda Tangan -->
    <div class="row mt-4">
        <div class="col-4">
            <div class="text-center">
                <div>Orang Tua / Wali</div>
                <div style="margin-top:4rem;border-top:1px solid #000;padding-top:0.25rem">
                    {{ $pengukuran->balita->nama_orang_tua }}
                </div>
            </div>
        </div>
        <div class="col-4"></div>
        <div class="col-4">
            <div class="text-center">
                <div>Petugas Posyandu</div>
                <div style="margin-top:4rem;border-top:1px solid #000;padding-top:0.25rem">
                    {{ $pengukuran->petugas->name ?? '-' }}
                </div>
            </div>
        </div>
    </div>

    <div class="footer text-center">
        Dicetak oleh SiGizi &mdash; Sistem Identifikasi Status Gizi Balita &bull; {{ now()->format('d/m/Y H:i') }} &bull;
    </div>
</body>
</html>