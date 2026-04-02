@extends('layouts.app')
@section('title', 'Dashboard Admin')
@section('breadcrumb')
<li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1><i class="fas fa-tachometer-alt mr-2 text-primary"></i> Dashboard Administrator</h1>
        <small class="text-muted">{{ now()->translatedFormat('l, d F Y') }}</small>
    </div>
    <div>
        <a href="{{ route('laporan.index') }}" class="btn btn-success">
            <i class="fas fa-file-download mr-1"></i> Unduh Laporan
        </a>
    </div>
</div>

<!-- Stat Cards Row 1 -->
<div class="row">
    <div class="col-xl-2 col-md-4">
        <div class="card border-left-primary stat-card">
            <div class="card-body py-3">
                <div class="stat-label text-primary">Total Balita</div>
                <div class="stat-value">{{ number_format($totalBalita) }}</div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4">
        <div class="card border-left-info stat-card">
            <div class="card-body py-3">
                <div class="stat-label text-info">Total Pengukuran</div>
                <div class="stat-value">{{ number_format($totalPengukuran) }}</div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4">
        <div class="card border-left-success stat-card">
            <div class="card-body py-3">
                <div class="stat-label text-success">Normal</div>
                <div class="stat-value">{{ number_format($distribusiStatus['Normal']) }}</div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4">
        <div class="card border-left-warning stat-card">
            <div class="card-body py-3">
                <div class="stat-label text-warning">Berisiko</div>
                <div class="stat-value">{{ number_format($totalBerisiko) }}</div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4">
        <div class="card border-left-danger stat-card">
            <div class="card-body py-3">
                <div class="stat-label text-danger">Stunting</div>
                <div class="stat-value">{{ number_format($totalStunting) }}</div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4">
        <div class="card border-left-secondary stat-card" style="border-left-color:#858796!important">
            <div class="card-body py-3">
                <div class="stat-label">Posyandu</div>
                <div class="stat-value">{{ $totalPosyandu }}</div>
                <small class="text-muted">{{ $totalPetugas }} Petugas</small>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row">
    <div class="col-xl-4 col-lg-5">
        <div class="card">
            <div class="card-header"><i class="fas fa-chart-pie mr-2"></i> Distribusi Status Gizi</div>
            <div class="card-body d-flex flex-column align-items-center">
                <canvas id="pieChart" width="250" height="250"></canvas>
                <div class="mt-3 w-100">
                    @php $total = array_sum($distribusiStatus) ?: 1; @endphp
                    <div class="d-flex justify-content-between mb-1"><span><i class="fas fa-circle text-success mr-1"></i> Normal</span><strong>{{ $distribusiStatus['Normal'] }} ({{ round($distribusiStatus['Normal']/$total*100) }}%)</strong></div>
                    <div class="d-flex justify-content-between mb-1"><span><i class="fas fa-circle text-warning mr-1"></i> Berisiko</span><strong>{{ $distribusiStatus['Berisiko'] }} ({{ round($distribusiStatus['Berisiko']/$total*100) }}%)</strong></div>
                    <div class="d-flex justify-content-between"><span><i class="fas fa-circle text-danger mr-1"></i> Stunting</span><strong>{{ $distribusiStatus['Stunting'] }} ({{ round($distribusiStatus['Stunting']/$total*100) }}%)</strong></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-8 col-lg-7">
        <div class="card">
            <div class="card-header"><i class="fas fa-chart-line mr-2"></i> Tren Pengukuran Bulanan</div>
            <div class="card-body">
                <canvas id="trendChart" height="100"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header"><i class="fas fa-chart-bar mr-2"></i> Distribusi Indikator Status Gizi</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4"><h6 class="text-center text-secondary" style="font-size:0.8rem;font-weight:800">BB/U</h6><canvas id="bbuChart" height="200"></canvas></div>
                    <div class="col-md-4"><h6 class="text-center text-secondary" style="font-size:0.8rem;font-weight:800">TB/U</h6><canvas id="tbuChart" height="200"></canvas></div>
                    <div class="col-md-4"><h6 class="text-center text-secondary" style="font-size:0.8rem;font-weight:800">BB/TB</h6><canvas id="bbtbChart" height="200"></canvas></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Links -->
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><i class="fas fa-link mr-2"></i> Akses Cepat</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6 mb-3">
                        <a href="{{ route('balita.index') }}" class="btn btn-outline-primary btn-block">
                            <i class="fas fa-child mr-2"></i>Data Balita
                        </a>
                    </div>
                    <div class="col-6 mb-3">
                        <a href="{{ route('pengukuran.index') }}" class="btn btn-outline-info btn-block">
                            <i class="fas fa-weight mr-2"></i>Pengukuran
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('admin.posyandu.index') }}" class="btn btn-outline-success btn-block">
                            <i class="fas fa-hospital mr-2"></i>Posyandu
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-block">
                            <i class="fas fa-users mr-2"></i>Petugas
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body" style="font-size:0.8rem">
                <table class="table table-sm table-bordered mb-0">
                    <thead class="thead-light"><tr><th>Indikator</th><th>Kategori</th><th>Z-Score</th></tr></thead>
                    <tbody>
                        <tr><td rowspan="3">TB/U</td><td><span class="badge badge-danger">Sangat Pendek</span></td><td>&lt; -3 SD</td></tr>
                        <tr><td><span class="badge badge-warning">Pendek</span></td><td>-3 s/d &lt; -2 SD</td></tr>
                        <tr><td><span class="badge badge-success">Normal</span></td><td>-2 s/d +3 SD</td></tr>
                        <tr><td rowspan="2">BB/U</td><td><span class="badge badge-danger">Gizi Buruk</span></td><td>&lt; -3 SD</td></tr>
                        <tr><td><span class="badge badge-warning">Gizi Kurang</span></td><td>-3 s/d &lt; -2 SD</td></tr>
                        <tr><td rowspan="2">BB/TB</td><td><span class="badge badge-danger">Sangat Kurus</span></td><td>&lt; -3 SD</td></tr>
                        <tr><td><span class="badge badge-warning">Kurus</span></td><td>-3 s/d &lt; -2 SD</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const distribusi = @json($distribusiStatus);
const tren = @json($trenBulanan);
const indikator = @json($distribusiIndikator);

new Chart(document.getElementById('pieChart'), {
    type: 'doughnut',
    data: {
        labels: ['Normal', 'Berisiko', 'Stunting'],
        datasets: [{ data: [distribusi.Normal, distribusi.Berisiko, distribusi.Stunting], backgroundColor: ['#1cc88a', '#f6c23e', '#e74a3b'], borderWidth: 3, borderColor: '#fff' }]
    },
    options: { responsive: true, cutout: '70%', plugins: { legend: { display: false } } }
});

new Chart(document.getElementById('trendChart'), {
    type: 'line',
    data: {
        labels: tren.map(d => d.bulan),
        datasets: [{ label: 'Pengukuran', data: tren.map(d => d.total), borderColor: '#4e73df', backgroundColor: 'rgba(78,115,223,0.1)', fill: true, tension: 0.3, pointRadius: 5, pointBackgroundColor: '#4e73df' }]
    },
    options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true }, x: { grid: { display: false } } } }
});

new Chart(document.getElementById('bbuChart'), { type: 'bar', data: { labels: Object.keys(indikator.bbu), datasets: [{ data: Object.values(indikator.bbu), backgroundColor: ['#e74a3b','#f6c23e','#1cc88a','#f6c23e','#e74a3b'], borderRadius: 4 }] }, options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } } });
new Chart(document.getElementById('tbuChart'), { type: 'bar', data: { labels: Object.keys(indikator.tbu), datasets: [{ data: Object.values(indikator.tbu), backgroundColor: ['#e74a3b','#f6c23e','#1cc88a','#36b9cc'], borderRadius: 4 }] }, options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } } });
new Chart(document.getElementById('bbtbChart'), { type: 'bar', data: { labels: Object.keys(indikator.bbtb), datasets: [{ data: Object.values(indikator.bbtb), backgroundColor: ['#e74a3b','#f6c23e','#1cc88a','#f6c23e','#e74a3b'], borderRadius: 4 }] }, options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } } });
</script>
@endpush