@extends('layouts.app')
@section('title', 'Hasil Laporan')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('laporan.index') }}">Laporan</a></li>
<li class="breadcrumb-item active">Hasil</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1><i class="fas fa-chart-bar mr-2 text-success"></i> Laporan Status Gizi</h1>
        <small class="text-muted">
            Periode: {{ \Carbon\Carbon::parse($params['tanggal_dari'])->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse($params['tanggal_sampai'])->format('d/m/Y') }}
            @if($posyandu) &mdash; {{ $posyandu->nama }} @endif
        </small>
    </div>
    <div class="d-flex" style="gap:0.5rem">
        <button onclick="window.print()" class="btn btn-secondary no-print">
            <i class="fas fa-print mr-1"></i> Cetak
        </button>
        <a href="{{ route('laporan.index') }}" class="btn btn-outline-secondary no-print">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>
</div>

<!-- Summary Cards -->
<div class="row">
    <div class="col-md-3">
        <div class="card border-left-primary stat-card">
            <div class="card-body">
                <div class="stat-label text-primary">Total Pengukuran</div>
                <div class="stat-value">{{ $stats['total'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-left-success stat-card">
            <div class="card-body">
                <div class="stat-label text-success">Normal</div>
                <div class="stat-value">{{ $stats['normal'] }}</div>
                @if($stats['total']>0)<small class="text-muted">{{ round($stats['normal']/$stats['total']*100) }}%</small>@endif
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-left-warning stat-card">
            <div class="card-body">
                <div class="stat-label text-warning">Berisiko</div>
                <div class="stat-value">{{ $stats['berisiko'] }}</div>
                @if($stats['total']>0)<small class="text-muted">{{ round($stats['berisiko']/$stats['total']*100) }}%</small>@endif
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-left-danger stat-card">
            <div class="card-body">
                <div class="stat-label text-danger">Stunting</div>
                <div class="stat-value">{{ $stats['stunting'] }}</div>
                @if($stats['total']>0)<small class="text-muted">{{ round($stats['stunting']/$stats['total']*100) }}%</small>@endif
            </div>
        </div>
    </div>
</div>

<!-- Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-table mr-2"></i> Data Pengukuran</span>
        <small class="text-muted">{{ $pengukuran->count() }} record</small>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0" style="font-size:0.8rem">
                <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>Tgl Ukur</th>
                        <th>Nama Balita</th>
                        <th>JK</th>
                        <th>Umur</th>
                        <th>BB</th>
                        <th>TB</th>
                        <th>BB/U</th>
                        <th>TB/U</th>
                        <th>BB/TB</th>
                        <th>Status</th>
                        <th>Posyandu</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengukuran as $i => $p)
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td>{{ $p->tanggal_ukur->format('d/m/Y') }}</td>
                        <td><strong>{{ $p->balita->nama }}</strong></td>
                        <td>{{ $p->balita->jenis_kelamin }}</td>
                        <td>{{ $p->umur_bulan }} bln</td>
                        <td>{{ $p->berat_badan }} kg</td>
                        <td>{{ $p->tinggi_badan }} cm</td>
                        <td><span class="badge {{ $p->kategori_bbu_badge }}" style="font-size:0.7rem">{{ $p->kategori_bbu }}</span></td>
                        <td><span class="badge {{ $p->kategori_tbu_badge }}" style="font-size:0.7rem">{{ $p->kategori_tbu }}</span></td>
                        <td><span class="badge {{ $p->kategori_bbtb_badge }}" style="font-size:0.7rem">{{ $p->kategori_bbtb }}</span></td>
                        <td><span class="badge {{ $p->status_badge_class }}">{{ $p->status_stunting }}</span></td>
                        <td>{{ $p->balita->posyandu->nama ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="12" class="text-center text-muted py-3">Tidak ada data untuk periode ini</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection