<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Gizi Balita</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Nunito', sans-serif; font-size: 11px; color: #2d3748; }
        .header { border-bottom: 3px solid #4e73df; padding-bottom: 0.75rem; margin-bottom: 1rem; }
        .logo-text { font-size: 1.2rem; font-weight: 900; color: #4e73df; }
        .summary-box { background: #f8f9fc; border: 1px solid #e3e6f0; border-radius: 0.35rem; padding: 0.75rem; text-align: center; }
        .summary-val { font-size: 1.5rem; font-weight: 800; }
        .badge-success { background: #28a745; color: #fff; padding: 2px 6px; border-radius: 20px; font-size: 0.7rem; }
        .badge-warning { background: #ffc107; color: #fff; padding: 2px 6px; border-radius: 20px; font-size: 0.7rem; }
        .badge-danger  { background: #dc3545; color: #fff; padding: 2px 6px; border-radius: 20px; font-size: 0.7rem; }
        .badge-info    { background: #17a2b8; color: #fff; padding: 2px 6px; border-radius: 20px; font-size: 0.7rem; }
        .footer { border-top: 1px solid #e3e6f0; margin-top: 1.5rem; padding-top: 0.5rem; font-size: 0.65rem; color: #718096; text-align: center; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body class="p-3">

    <div class="no-print mb-3">
        <button onclick="window.print()" class="btn btn-primary btn-sm">🖨️ Cetak / Simpan PDF</button>
        <a href="{{ route('laporan.index') }}" class="btn btn-secondary btn-sm ml-2">← Kembali</a>
    </div>

    <!-- Header -->
    <div class="header d-flex justify-content-between align-items-center">
        <div>
            <div class="logo-text">🍼 SiGizi Balita</div>
            <div style="font-size:0.75rem;color:#718096">Sistem Identifikasi Status Gizi Balita</div>
        </div>
        <div class="text-right">
            <div style="font-weight:800;font-size:1rem">LAPORAN STATUS GIZI BALITA</div>
            <div style="font-size:0.75rem">
                Periode: {{ \Carbon\Carbon::parse($params['tanggal_dari'])->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse($params['tanggal_sampai'])->format('d/m/Y') }}<br>
                Posyandu: {{ $posyandu ? $posyandu->nama_posyandu : 'Semua Posyandu' }}<br>
                Dicetak: {{ now()->format('d/m/Y H:i') }}
            </div>
        </div>
    </div>

    <!-- Summary -->
    <div class="row mb-3">
        <div class="col-3">
            <div class="summary-box">
                <div class="text-muted" style="font-size:0.7rem;font-weight:700">TOTAL</div>
                <div class="summary-val text-primary">{{ $stats['total'] }}</div>
            </div>
        </div>
        <div class="col-3">
            <div class="summary-box">
                <div class="text-muted" style="font-size:0.7rem;font-weight:700">NORMAL</div>
                <div class="summary-val text-success">{{ $stats['normal'] }}</div>
                @if($stats['total']>0)<div style="font-size:0.7rem;color:#718096">{{ round($stats['normal']/$stats['total']*100) }}%</div>@endif
            </div>
        </div>
        <div class="col-3">
            <div class="summary-box">
                <div class="text-muted" style="font-size:0.7rem;font-weight:700">BERISIKO</div>
                <div class="summary-val text-warning">{{ $stats['berisiko'] }}</div>
                @if($stats['total']>0)<div style="font-size:0.7rem;color:#718096">{{ round($stats['berisiko']/$stats['total']*100) }}%</div>@endif
            </div>
        </div>
        <div class="col-3">
            <div class="summary-box">
                <div class="text-muted" style="font-size:0.7rem;font-weight:700">STUNTING</div>
                <div class="summary-val text-danger">{{ $stats['stunting'] }}</div>
                @if($stats['total']>0)<div style="font-size:0.7rem;color:#718096">{{ round($stats['stunting']/$stats['total']*100) }}%</div>@endif
            </div>
        </div>
    </div>

    <!-- Table -->
    <table class="table table-bordered table-sm" style="font-size:10px">
        <thead style="background:#4e73df;color:#fff">
            <tr>
                <th>No</th>
                <th>Tgl Ukur</th>
                <th>Nama Balita</th>
                <th>JK</th>
                <th>Umur</th>
                <th>BB (kg)</th>
                <th>TB (cm)</th>
                <th>BB/U</th>
                <th>TB/U</th>
                <th>BB/TB</th>
                <th>Z-BB/U</th>
                <th>Z-TB/U</th>
                <th>Z-BB/TB</th>
                <th>Status</th>
                <th>Posyandu</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pengukuran as $i => $p)
            <tr style="{{ $p->status_stunting === 'Stunting' ? 'background:#fff5f5' : ($p->status_stunting === 'Berisiko Gangguan Pertumbuhan' ? 'background:#fffbf0' : '') }}">
                <td>{{ $i+1 }}</td>
                <td>{{ $p->tanggal_ukur->format('d/m/Y') }}</td>
                <td><strong>{{ $p->balita->nama_balita }}</strong></td>
                <td>{{ $p->balita->jenis_kelamin }}</td>
                <td>{{ $p->umur_bulan }} bln</td>
                <td>{{ $p->berat_badan }}</td>
                <td>{{ $p->tinggi_badan }}</td>
                <td>
                    @php $b1 = match($p->kategori_bbu) { 'Gizi Buruk','Gizi Kurang' => 'badge-danger', 'Gizi Baik' => 'badge-success', default => 'badge-warning' }; @endphp
                    <span class="{{ $b1 }}">{{ $p->kategori_bbu }}</span>
                </td>
                <td>
                    @php $b2 = match($p->kategori_tbu) { 'Sangat Pendek','Pendek' => 'badge-danger', 'Normal' => 'badge-success', default => 'badge-info' }; @endphp
                    <span class="{{ $b2 }}">{{ $p->kategori_tbu }}</span>
                </td>
                <td>
                    @php $b3 = match($p->kategori_bbtb) { 'Sangat Kurus','Kurus' => 'badge-danger', 'Normal' => 'badge-success', default => 'badge-warning' }; @endphp
                    <span class="{{ $b3 }}">{{ $p->kategori_bbtb }}</span>
                </td>
                <td>{{ $p->zscore_bbu }}</td>
                <td>{{ $p->zscore_tbu }}</td>
                <td>{{ $p->zscore_bbtb }}</td>
                <td>
                    @php $bs = match($p->status_stunting) { 'Stunting' => 'badge-danger', 'Berisiko Gangguan Pertumbuhan' => 'badge-warning', default => 'badge-success' }; @endphp
                    <span class="{{ $bs }}">{{ $p->status_stunting }}</span>
                </td>
                <td>{{ $p->balita->posyandu->nama_posyandu ?? '-' }}</td>
            </tr>
            @empty
            <tr><td colspan="15" class="text-center text-muted py-2">Tidak ada data</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        SiGizi — Sistem Identifikasi Status Gizi Balita &bull; 
    </div>
</body>
</html>