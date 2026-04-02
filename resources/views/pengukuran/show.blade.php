@extends('layouts.app')
@section('title', 'Hasil Pengukuran')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('pengukuran.index') }}">Pengukuran</a></li>
<li class="breadcrumb-item active">Hasil #{{ $pengukuran->id }}</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1><i class="fas fa-clipboard-check mr-2 text-success"></i> Hasil Identifikasi Status Gizi</h1>
        <small class="text-muted">Pengukuran tanggal {{ $pengukuran->tanggal_ukur->format('d F Y') }}</small>
    </div>
    <div class="d-flex" style="gap:0.5rem">
        <a href="{{ route('pengukuran.cetak', $pengukuran) }}" class="btn btn-secondary" target="_blank">
            <i class="fas fa-print mr-1"></i> Cetak
        </a>
        <a href="{{ route('pengukuran.edit', $pengukuran) }}" class="btn btn-warning">
            <i class="fas fa-edit mr-1"></i> Edit
        </a>
    </div>
</div>

{{-- ── STATUS BANNER ── --}}
@php
$statusBg   = match($pengukuran->status_stunting) {
    'Stunting'                       => 'border-left-danger',
    'Berisiko Gangguan Pertumbuhan'  => 'border-left-warning',
    default                          => 'border-left-success',
};
$statusIcon = match($pengukuran->status_stunting) {
    'Stunting'                       => 'fa-exclamation-triangle text-danger',
    'Berisiko Gangguan Pertumbuhan'  => 'fa-exclamation-circle text-warning',
    default                          => 'fa-check-circle text-success',
};
$statusClass = match($pengukuran->status_stunting) {
    'Stunting'                       => 'stunting',
    'Berisiko Gangguan Pertumbuhan'  => 'berisiko',
    default                          => 'normal',
};
@endphp

<div class="card {{ $statusBg }} mb-3">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-1 text-center">
                <i class="fas {{ $statusIcon }} fa-3x"></i>
            </div>
            <div class="col-md-8">
                <h4 class="font-weight-bold mb-1">{{ $pengukuran->status_stunting }}</h4>
                @if($pengukuran->status_stunting === 'Stunting')
                    <p class="mb-0 text-muted">TB/U menunjukkan <strong>{{ $pengukuran->kategori_tbu }}</strong>. Balita terindikasi stunting. Segera konsultasikan dengan tenaga kesehatan.</p>
                @elseif($pengukuran->status_stunting === 'Berisiko Gangguan Pertumbuhan')
                    <p class="mb-0 text-muted">Meskipun tinggi badan normal, indikator BB/U atau BB/TB menunjukkan gangguan nutrisi. Perlu pemantauan lebih lanjut dan perbaikan gizi.</p>
                @else
                    <p class="mb-0 text-muted">Seluruh indikator antropometri dalam batas normal. Pertahankan pola makan bergizi dan lakukan pemantauan rutin setiap bulan.</p>
                @endif
            </div>
            <div class="col-md-3 text-right">
                <div class="status-badge {{ $statusClass }}">
                    <i class="fas fa-baby mr-1"></i> {{ $pengukuran->status_stunting }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── ROW 1: IDENTITAS + ANTROPOMETRI (tanpa grafik) ── --}}
<div class="row">

    {{-- Identitas Balita --}}
    <div class="col-lg-6 col-md-12 mb-3">
        <div class="card h-100">
            <div class="card-header"><i class="fas fa-user-circle mr-2"></i> Identitas Balita</div>
            <div class="card-body" style="font-size:0.85rem">
                <table class="table table-sm table-borderless mb-0">
                    <tr><td class="text-muted" width="110">Nama</td><td><strong>{{ $pengukuran->balita->nama }}</strong></td></tr>
                    <tr><td class="text-muted">Kode</td><td><code>{{ $pengukuran->balita->kode_balita }}</code></td></tr>
                    <tr><td class="text-muted">Jenis Kelamin</td><td>{{ $pengukuran->balita->jenis_kelamin_label }}</td></tr>
                    <tr><td class="text-muted">Tgl Lahir</td><td>{{ $pengukuran->balita->tanggal_lahir->format('d/m/Y') }}</td></tr>
                    <tr><td class="text-muted">Umur Ukur</td><td><strong>{{ $pengukuran->umur_bulan }} bulan</strong></td></tr>
                    <tr><td class="text-muted">Posyandu</td><td>{{ $pengukuran->balita->posyandu->nama ?? '-' }}</td></tr>
                    <tr><td class="text-muted">Petugas</td><td>{{ $pengukuran->petugas->name ?? '-' }}</td></tr>
                </table>
            </div>
        </div>
    </div>

    {{-- Data Antropometri --}}
    <div class="col-lg-6 col-md-12 mb-3">
        <div class="card h-100">
            <div class="card-header"><i class="fas fa-ruler-combined mr-2"></i> Data Antropometri</div>
            <div class="card-body">
                <div class="row text-center mb-3">
                    <div class="col-6">
                        <div style="background:#f8f9fc;border-radius:0.5rem;padding:1rem">
                            <i class="fas fa-weight fa-2x text-primary mb-2"></i>
                            <div style="font-size:1.8rem;font-weight:800;color:#2d3748">{{ $pengukuran->berat_badan }}</div>
                            <div class="text-muted" style="font-size:0.72rem">kg — Berat Badan</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div style="background:#f8f9fc;border-radius:0.5rem;padding:1rem">
                            <i class="fas fa-ruler-vertical fa-2x text-success mb-2"></i>
                            <div style="font-size:1.8rem;font-weight:800;color:#2d3748">{{ $pengukuran->tinggi_badan }}</div>
                            <div class="text-muted" style="font-size:0.72rem">cm — Tinggi Badan</div>
                        </div>
                    </div>
                </div>

                {{-- Ringkasan z-score per indikator --}}
                <div class="row text-center mt-2">
                    <div class="col-4">
                        <div class="p-2" style="background:#f8f9fc;border-radius:0.4rem;font-size:0.78rem">
                            <div class="text-muted mb-1">BB/U</div>
                            <div class="font-weight-bold"
                                style="color:{{ $zscore_bbu !== null && $zscore_bbu >= -2 && $zscore_bbu <= 2 ? '#1a8a54' : ($zscore_bbu !== null && ($zscore_bbu < -3 || $zscore_bbu > 3) ? '#dc3545' : '#fd7e14') }}">
                                {{ $kategori_bbu }}
                            </div>
                            @if($zscore_bbu !== null)
                            <div class="text-muted" style="font-size:0.7rem">z = {{ number_format($zscore_bbu,2) }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-2" style="background:#f8f9fc;border-radius:0.4rem;font-size:0.78rem">
                            <div class="text-muted mb-1">TB/U</div>
                            <div class="font-weight-bold"
                                style="color:{{ $zscore_tbu !== null && $zscore_tbu >= -2 && $zscore_tbu <= 2 ? '#1a8a54' : ($zscore_tbu !== null && ($zscore_tbu < -3 || $zscore_tbu > 3) ? '#dc3545' : '#fd7e14') }}">
                                {{ $kategori_tbu }}
                            </div>
                            @if($zscore_tbu !== null)
                            <div class="text-muted" style="font-size:0.7rem">z = {{ number_format($zscore_tbu,2) }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-2" style="background:#f8f9fc;border-radius:0.4rem;font-size:0.78rem">
                            <div class="text-muted mb-1">BB/TB</div>
                            <div class="font-weight-bold"
                                style="color:{{ $zscore_bbtb !== null && $zscore_bbtb >= -2 && $zscore_bbtb <= 2 ? '#1a8a54' : ($zscore_bbtb !== null && ($zscore_bbtb < -3 || $zscore_bbtb > 3) ? '#dc3545' : '#fd7e14') }}">
                                {{ $kategori_bbtb }}
                            </div>
                            @if($zscore_bbtb !== null)
                            <div class="text-muted" style="font-size:0.7rem">z = {{ number_format($zscore_bbtb,2) }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                @if($pengukuran->catatan)
                <div class="alert alert-light mt-3 mb-0" style="font-size:0.8rem">
                    <i class="fas fa-sticky-note mr-1"></i> <em>{{ $pengukuran->catatan }}</em>
                </div>
                @endif
            </div>
        </div>
    </div>

</div>

{{-- ── ROW 2: GRAFIK PERTUMBUHAN (full width, 3 kolom sejajar) ── --}}
@include('pengukuran.partials.zscore-chart', [
    'zscore_bbu'    => $zscore_bbu,
    'zscore_tbu'    => $zscore_tbu,
    'zscore_bbtb'   => $zscore_bbtb,
    'kategori_bbu'  => $kategori_bbu,
    'kategori_tbu'  => $kategori_tbu,
    'kategori_bbtb' => $kategori_bbtb,
])

{{-- ── BOTTOM ACTIONS ── --}}
<div class="d-flex justify-content-between mt-3">
    <a href="{{ route('balita.show', $pengukuran->balita) }}" class="btn btn-outline-primary">
        <i class="fas fa-arrow-left mr-1"></i> Kembali ke Profil Balita
    </a>
    <a href="{{ route('pengukuran.create', ['balita_id' => $pengukuran->balita_id]) }}" class="btn btn-success">
        <i class="fas fa-plus mr-1"></i> Pengukuran Baru
    </a>
</div>
@endsection