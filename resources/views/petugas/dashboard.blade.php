@extends('layouts.app')

@section('title', 'Dashboard')

@section('breadcrumb')
<li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<!-- Page Header -->
<div class="page-header">
    <div>
        <h1><i class="fas fa-tachometer-alt mr-2 text-primary"></i> Dashboard</h1>
        <small class="text-muted">Selamat datang, <strong>{{ auth()->user()->name }}</strong> &mdash; {{ now()->translatedFormat('l, d F Y') }}</small>
    </div>
    <a href="{{ route('pengukuran.create') }}" class="btn btn-primary">
        <i class="fas fa-plus mr-1"></i> Input Pengukuran
    </a>
</div>

<!-- Stat Cards -->
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card border-left-primary stat-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-label text-primary">Total Balita</div>
                        <div class="stat-value">{{ number_format($totalBalita) }}</div>
                        <small class="text-muted">Terdaftar di posyandu</small>
                    </div>
                    <i class="fas fa-child stat-icon text-primary"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card border-left-info stat-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-label text-info">Total Pengukuran</div>
                        <div class="stat-value">{{ number_format($totalPengukuran) }}</div>
                        <small class="text-muted">Hari ini: {{ $pengukuranHariIni }}</small>
                    </div>
                    <i class="fas fa-weight stat-icon text-info"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card border-left-warning stat-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-label text-warning">Berisiko</div>
                        <div class="stat-value">{{ number_format($totalBerisiko) }}</div>
                        <small class="text-muted">Gangguan pertumbuhan</small>
                    </div>
                    <i class="fas fa-exclamation-triangle stat-icon text-warning"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card border-left-danger stat-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-label text-danger">Stunting</div>
                        <div class="stat-value">{{ number_format($totalStunting) }}</div>
                        <small class="text-muted">TB/U pendek / sangat pendek</small>
                    </div>
                    <i class="fas fa-chart-line stat-icon text-danger"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row 1 -->
<div class="row">
    <div class="col-xl-4 col-lg-5">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-chart-pie mr-2"></i> Distribusi Status Gizi
            </div>
            <div class="card-body d-flex flex-column align-items-center">
                <canvas id="pieChart" width="250" height="250"></canvas>
                <div class="mt-3 w-100">
                    @php $total = array_sum($distribusiStatus) ?: 1; @endphp
                    <div class="d-flex justify-content-between mb-1">
                        <span><i class="fas fa-circle text-success mr-1"></i> Normal</span>
                        <strong>{{ $distribusiStatus['Normal'] }} ({{ round($distribusiStatus['Normal']/$total*100) }}%)</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span><i class="fas fa-circle text-warning mr-1"></i> Berisiko</span>
                        <strong>{{ $distribusiStatus['Berisiko'] }} ({{ round($distribusiStatus['Berisiko']/$total*100) }}%)</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span><i class="fas fa-circle text-danger mr-1"></i> Stunting</span>
                        <strong>{{ $distribusiStatus['Stunting'] }} ({{ round($distribusiStatus['Stunting']/$total*100) }}%)</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-8 col-lg-7">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-chart-line mr-2"></i> Tren Pengukuran Bulanan (12 Bulan Terakhir)
            </div>
            <div class="card-body">
                <canvas id="trendChart" height="100"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row 2 -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-chart-bar mr-2"></i> Distribusi Indikator Status Gizi
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <h6 class="text-center font-weight-bold text-secondary mb-2" style="font-size:0.8rem">BB/U (Berat Badan / Umur)</h6>
                        <canvas id="bbuChart" height="200"></canvas>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-center font-weight-bold text-secondary mb-2" style="font-size:0.8rem">TB/U (Tinggi Badan / Umur)</h6>
                        <canvas id="tbuChart" height="200"></canvas>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-center font-weight-bold text-secondary mb-2" style="font-size:0.8rem">BB/TB (Berat Badan / Tinggi Badan)</h6>
                        <canvas id="bbtbChart" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Pengukuran -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-list mr-2"></i> Pengukuran Terbaru</span>
        <a href="{{ route('pengukuran.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Balita</th>
                        <th>Umur</th>
                        <th>BB (kg)</th>
                        <th>TB (cm)</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentPengukuran as $p)
                    <tr>
                        <td>{{ $p->tanggal_ukur->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('balita.show', $p->balita) }}" class="font-weight-bold">
                                {{ $p->balita->nama_balita }}
                            </a>
                            <small class="d-block text-muted">{{ $p->balita->kode_balita }}</small>
                        </td>
                        <td>{{ $p->umur_bulan }} bln</td>
                        <td>{{ $p->berat_badan }}</td>
                        <td>{{ $p->tinggi_badan }}</td>
                        <td>
                            <span class="badge {{ $p->status_badge_class }}">
                                {{ $p->status_stunting }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('pengukuran.show', $p) }}" class="btn btn-xs btn-info">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-3">Belum ada data pengukuran</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Data dari PHP
const distribusi = @json($distribusiStatus);
const tren = @json($trenBulanan);
const indikator = @json($distribusiIndikator);

// Pie Chart - Distribusi Status
new Chart(document.getElementById('pieChart'), {
    type: 'doughnut',
    data: {
        labels: ['Normal', 'Berisiko', 'Stunting'],
        datasets: [{
            data: [distribusi.Normal, distribusi.Berisiko, distribusi.Stunting],
            backgroundColor: ['#1cc88a', '#f6c23e', '#e74a3b'],
            borderWidth: 3,
            borderColor: '#fff',
        }]
    },
    options: {
        responsive: true,
        cutout: '70%',
        plugins: { legend: { display: false } }
    }
});

// Trend Chart
new Chart(document.getElementById('trendChart'), {
    type: 'line',
    data: {
        labels: tren.map(d => d.bulan),
        datasets: [{
            label: 'Jumlah Pengukuran',
            data: tren.map(d => d.total),
            borderColor: '#4e73df',
            backgroundColor: 'rgba(78,115,223,0.1)',
            fill: true,
            tension: 0.3,
            pointRadius: 5,
            pointBackgroundColor: '#4e73df',
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: '#f0f0f0' } },
            x: { grid: { display: false } }
        }
    }
});

// BB/U Chart
new Chart(document.getElementById('bbuChart'), {
    type: 'bar',
    data: {
        labels: Object.keys(indikator.bbu),
        datasets: [{
            data: Object.values(indikator.bbu),
            backgroundColor: ['#e74a3b','#f6c23e','#1cc88a','#f6c23e','#e74a3b'],
            borderRadius: 4,
        }]
    },
    options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
});

// TB/U Chart
new Chart(document.getElementById('tbuChart'), {
    type: 'bar',
    data: {
        labels: Object.keys(indikator.tbu),
        datasets: [{
            data: Object.values(indikator.tbu),
            backgroundColor: ['#e74a3b','#f6c23e','#1cc88a','#36b9cc'],
            borderRadius: 4,
        }]
    },
    options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
});

// BB/TB Chart
new Chart(document.getElementById('bbtbChart'), {
    type: 'bar',
    data: {
        labels: Object.keys(indikator.bbtb),
        datasets: [{
            data: Object.values(indikator.bbtb),
            backgroundColor: ['#e74a3b','#f6c23e','#1cc88a','#f6c23e','#e74a3b'],
            borderRadius: 4,
        }]
    },
    options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
});
</script>
@endpush