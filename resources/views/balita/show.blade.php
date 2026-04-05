@extends('layouts.app')
@section('title', 'Detail Balita')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('balita.index') }}">Data Balita</a></li>
<li class="breadcrumb-item active">{{ $balita->nama }}</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1><i class="fas fa-child mr-2 text-primary"></i> {{ $balita->nama }}</h1>
        <small class="text-muted">{{ $balita->kode_balita }}</small>
    </div>
    <div class="d-flex" style="gap:0.5rem">
        <a href="{{ route('pengukuran.create', ['balita_id' => $balita->id]) }}" class="btn btn-success">
            <i class="fas fa-plus mr-1"></i> Input Pengukuran
        </a>
        <a href="{{ route('balita.edit', $balita) }}" class="btn btn-warning">
            <i class="fas fa-edit mr-1"></i> Edit
        </a>
    </div>
</div>

<div class="row">
    <!-- Info Balita -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><i class="fas fa-id-card mr-2"></i> Identitas Balita</div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="avatar mx-auto mb-2" style="width:5rem;height:5rem;font-size:2rem;background:{{ $balita->jenis_kelamin === 'L' ? '#36b9cc' : '#e83e8c' }}">
                        <i class="fas fa-{{ $balita->jenis_kelamin === 'L' ? 'mars' : 'venus' }}"></i>
                    </div>
                    <h5 class="font-weight-bold mb-0">{{ $balita->nama }}</h5>
                    <small class="text-muted">{{ $balita->jenis_kelamin_label }}</small>
                </div>

                <table class="table table-sm table-borderless" style="font-size:0.85rem">
                    <tr><td class="text-muted" style="width:40%">Kode</td><td><code>{{ $balita->kode_balita }}</code></td></tr>
                    <tr><td class="text-muted">Tgl Lahir</td><td>{{ $balita->tanggal_lahir->format('d F Y') }}</td></tr>
                    <tr><td class="text-muted">Umur</td><td><strong>{{ $balita->umur_formatted }}</strong></td></tr>
                    <tr><td class="text-muted">Posyandu</td><td>{{ $balita->posyandu->nama ?? '-' }}</td></tr>
                    <tr><td class="text-muted">Orang Tua</td><td>{{ $balita->nama_orang_tua }}</td></tr>
                    <tr><td class="text-muted">No. HP</td><td>{{ $balita->no_hp ?? '-' }}</td></tr>
                    <tr><td class="text-muted">Alamat</td><td>{{ $balita->alamat ?? '-' }}</td></tr>
                    <tr><td class="text-muted">Petugas</td><td>{{ $balita->creator->nama ?? '-' }}</td></tr>
                </table>
            </div>
        </div>

        <!-- Status Terakhir -->
        @if($balita->pengukuranTerakhir)
        @php $last = $balita->pengukuranTerakhir; @endphp
        <div class="card">
            <div class="card-header"><i class="fas fa-heartbeat mr-2"></i> Status Gizi Terakhir</div>
            <div class="card-body text-center">
                @php
                $statusClass = match($last->status_stunting) {
                    'Stunting' => 'stunting',
                    'Berisiko Gangguan Pertumbuhan' => 'berisiko',
                    default => 'normal'
                };
                $statusIcon = match($last->status_stunting) {
                    'Stunting' => 'fa-exclamation-triangle',
                    'Berisiko Gangguan Pertumbuhan' => 'fa-exclamation-circle',
                    default => 'fa-check-circle'
                };
                @endphp
                <div class="status-badge {{ $statusClass }} d-inline-flex mb-3">
                    <i class="fas {{ $statusIcon }}"></i>
                    {{ $last->status_stunting }}
                </div>
                <div class="row text-center" style="font-size:0.8rem">
                    <div class="col-4">
                        <div class="text-muted">BB</div>
                        <strong>{{ $last->berat_badan }} kg</strong>
                    </div>
                    <div class="col-4">
                        <div class="text-muted">TB</div>
                        <strong>{{ $last->tinggi_badan }} cm</strong>
                    </div>
                    <div class="col-4">
                        <div class="text-muted">Umur</div>
                        <strong>{{ $last->umur_bulan }} bln</strong>
                    </div>
                </div>
                <hr>
                <div style="font-size:0.8rem">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">BB/U:</span>
                        <span class="badge {{ $last->kategori_bbu_badge }}">{{ $last->kategori_bbu }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">TB/U:</span>
                        <span class="badge {{ $last->kategori_tbu_badge }}">{{ $last->kategori_tbu }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">BB/TB:</span>
                        <span class="badge {{ $last->kategori_bbtb_badge }}">{{ $last->kategori_bbtb }}</span>
                    </div>
                </div>
                <div class="mt-2">
                    <small class="text-muted">Diukur {{ $last->tanggal_ukur->format('d/m/Y') }}</small>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Grafik & Riwayat -->
    <div class="col-lg-8">
        <!-- Growth Charts -->
        <div class="card">
            <div class="card-header">
                <i class="fas fa-chart-line mr-2"></i> Grafik Pertumbuhan
                <ul class="nav nav-tabs card-header-tabs ml-auto" style="margin-top:-0.5rem;border:none">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#chartBBU">BB/U</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#chartTBU">TB/U</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#chartBBTB">BB/TB</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="chartBBU">
                        <canvas id="bbuChart" height="120"></canvas>
                    </div>
                    <div class="tab-pane fade" id="chartTBU">
                        <canvas id="tbuChart" height="120"></canvas>
                    </div>
                    <div class="tab-pane fade" id="chartBBTB">
                        <canvas id="bbtbChart" height="120"></canvas>
                    </div>
                </div>
                <small class="text-muted d-block mt-2">
                    <i class="fas fa-info-circle mr-1"></i>
                    Grafik menampilkan tren pertumbuhan berdasarkan riwayat pengukuran
                </small>
            </div>
        </div>

        <!-- Riwayat Pengukuran -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-history mr-2"></i> Riwayat Pengukuran</span>
                <span class="badge badge-primary">{{ $balita->pengukuran->count() }} pengukuran</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" style="font-size:0.83rem">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Umur</th>
                                <th>BB (kg)</th>
                                <th>TB (cm)</th>
                                <th>BB/U</th>
                                <th>TB/U</th>
                                <th>BB/TB</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($balita->pengukuran->sortByDesc('tanggal_ukur') as $p)
                            <tr>
                                <td>{{ $p->tanggal_ukur->format('d/m/Y') }}</td>
                                <td>{{ $p->umur_bulan }} bln</td>
                                <td>{{ $p->berat_badan }}</td>
                                <td>{{ $p->tinggi_badan }}</td>
                                <td><span class="badge {{ $p->kategori_bbu_badge }}" style="font-size:0.7rem">{{ $p->kategori_bbu }}</span></td>
                                <td><span class="badge {{ $p->kategori_tbu_badge }}" style="font-size:0.7rem">{{ $p->kategori_tbu }}</span></td>
                                <td><span class="badge {{ $p->kategori_bbtb_badge }}" style="font-size:0.7rem">{{ $p->kategori_bbtb }}</span></td>
                                <td><span class="badge {{ $p->status_badge_class }}" style="font-size:0.7rem">{{ $p->status_stunting }}</span></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('pengukuran.show', $p) }}" class="btn btn-info btn-xs"><i class="fas fa-eye"></i></a>
                                        <a href="{{ route('pengukuran.edit', $p) }}" class="btn btn-warning btn-xs"><i class="fas fa-edit"></i></a>
                                        <button onclick="confirmDeletePengukuran('{{ route('pengukuran.destroy', $p) }}')" class="btn btn-danger btn-xs"><i class="fas fa-trash"></i></button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="9" class="text-center text-muted py-3">Belum ada riwayat pengukuran</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<form id="deleteFormPengukuran" method="POST" style="display:none">@csrf @method('DELETE')</form>
@endsection

@push('scripts')
<script>
const pengData = @json($pengukuranData);

const labels = pengData.map(d => `${d.umur} bln`);
const bbData  = pengData.map(d => parseFloat(d.bb));
const tbData  = pengData.map(d => parseFloat(d.tb));
const zBBU    = pengData.map(d => parseFloat(d.zscore_bbu));
const zTBU    = pengData.map(d => parseFloat(d.zscore_tbu));
const zBBTB   = pengData.map(d => parseFloat(d.zscore_bbtb));

const chartConfig = (label, data, color) => ({
    type: 'line',
    data: {
        labels,
        datasets: [
            {
                label,
                data,
                borderColor: color,
                backgroundColor: color + '20',
                fill: true,
                tension: 0.3,
                pointRadius: 6,
                pointBackgroundColor: color,
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
            },
            {
                label: 'Z-Score -2 SD (Batas Bawah Normal)',
                data: new Array(data.length).fill(-2),
                borderColor: '#f6c23e',
                borderDash: [5,5],
                borderWidth: 1.5,
                pointRadius: 0,
                fill: false,
            },
            {
                label: 'Z-Score -3 SD (Sangat Kurang)',
                data: new Array(data.length).fill(-3),
                borderColor: '#e74a3b',
                borderDash: [5,5],
                borderWidth: 1.5,
                pointRadius: 0,
                fill: false,
            }
        ]
    },
    options: {
        responsive: true,
        interaction: { mode: 'index', intersect: false },
        plugins: {
            legend: { position: 'bottom', labels: { font: { size: 11 } } }
        },
        scales: {
            y: { grid: { color: '#f0f0f0' } },
            x: { grid: { display: false } }
        }
    }
});

new Chart(document.getElementById('bbuChart'), chartConfig('Berat Badan (kg)', bbData, '#4e73df'));
new Chart(document.getElementById('tbuChart'), chartConfig('Tinggi Badan (cm)', tbData, '#1cc88a'));
new Chart(document.getElementById('bbtbChart'), {
    type: 'line',
    data: {
        labels,
        datasets: [{
            label: 'Z-Score BB/TB',
            data: zBBTB,
            borderColor: '#36b9cc',
            backgroundColor: 'rgba(54,185,204,0.1)',
            fill: true,
            tension: 0.3,
            pointRadius: 6,
            pointBackgroundColor: '#36b9cc',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom' } },
        scales: {
            y: { grid: { color: '#f0f0f0' } },
            x: { grid: { display: false } }
        }
    }
});

function confirmDeletePengukuran(url) {
    if (confirm('Yakin hapus data pengukuran ini?')) {
        document.getElementById('deleteFormPengukuran').action = url;
        document.getElementById('deleteFormPengukuran').submit();
    }
}
</script>
@endpush