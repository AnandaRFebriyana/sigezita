@extends('layouts.app')
@section('title', 'Edit Pengukuran')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('pengukuran.index') }}">Pengukuran</a></li>
<li class="breadcrumb-item active">Edit #{{ $pengukuran->id }}</li>
@endsection

@section('content')
<div class="page-header">
    <h1><i class="fas fa-edit mr-2 text-warning"></i> Edit Data Pengukuran</h1>
</div>

<div class="row">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header">
                Edit Pengukuran: <strong>{{ $pengukuran->balita->nama_balita }}</strong>
            </div>
            <div class="card-body">
                <!-- Info Balita (readonly) -->
                <div class="alert alert-light mb-4" style="font-size:0.85rem">
                    <div class="row">
                        <div class="col-6">
                            <strong>{{ $pengukuran->balita->nama_balita }}</strong><br>
                            <small class="text-muted">{{ $pengukuran->balita->kode_balita }} &bull; {{ $pengukuran->balita->jenis_kelamin_label }}</small>
                        </div>
                        <div class="col-6 text-right">
                            <strong>Lahir: {{ $pengukuran->balita->tanggal_lahir->format('d/m/Y') }}</strong><br>
                            <small class="text-muted">Umur saat ini: {{ $pengukuran->balita->umur_bulan }} bulan</small>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('pengukuran.update', $pengukuran) }}">
                    @csrf @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Tanggal Pengukuran <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_ukur" id="tanggalUkur"
                                    class="form-control @error('tanggal_ukur') is-invalid @enderror"
                                    value="{{ old('tanggal_ukur', $pengukuran->tanggal_ukur->format('Y-m-d')) }}"
                                    max="{{ date('Y-m-d') }}" required
                                    data-tgl-lahir="{{ $pengukuran->balita->tanggal_lahir->format('Y-m-d') }}">
                                @error('tanggal_ukur')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Umur saat Pengukuran</label>
                                <div class="input-group">
                                    <input type="text" id="umurDisplay" class="form-control"
                                        value="{{ $pengukuran->umur_bulan }}" readonly style="background:#f8f9fc">
                                    <div class="input-group-append">
                                        <span class="input-group-text">bulan</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Berat Badan (BB) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" name="berat_badan" step="0.01" min="0.5" max="50"
                                        class="form-control @error('berat_badan') is-invalid @enderror"
                                        value="{{ old('berat_badan', $pengukuran->berat_badan) }}" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text">kg</span>
                                    </div>
                                </div>
                                @error('berat_badan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Tinggi/Panjang Badan (TB) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" name="tinggi_badan" step="0.1" min="30" max="150"
                                        class="form-control @error('tinggi_badan') is-invalid @enderror"
                                        value="{{ old('tinggi_badan', $pengukuran->tinggi_badan) }}" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text">cm</span>
                                    </div>
                                </div>
                                @error('tinggi_badan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Catatan</label>
                        <textarea name="catatan" class="form-control" rows="2">{{ old('catatan', $pengukuran->catatan) }}</textarea>
                    </div>

                    <div class="alert alert-warning" style="font-size:0.82rem">
                        <i class="fas fa-info-circle mr-1"></i>
                        Menyimpan perubahan akan <strong>menghitung ulang</strong> klasifikasi status gizi secara otomatis.
                    </div>

                    <div class="d-flex justify-content-end" style="gap:0.5rem">
                        <a href="{{ route('pengukuran.show', $pengukuran) }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-sync mr-1"></i> Update & Hitung Ulang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card">
            <div class="card-header"><i class="fas fa-history mr-2"></i> Hasil Sebelumnya</div>
            <div class="card-body" style="font-size:0.83rem">
                <table class="table table-sm table-borderless mb-0">
                    <tr><td class="text-muted">Tanggal</td><td>{{ $pengukuran->tanggal_ukur->format('d/m/Y') }}</td></tr>
                    <tr><td class="text-muted">Berat Badan</td><td>{{ $pengukuran->berat_badan }} kg</td></tr>
                    <tr><td class="text-muted">Tinggi Badan</td><td>{{ $pengukuran->tinggi_badan }} cm</td></tr>
                    <tr><td class="text-muted">BB/U</td><td><span class="badge {{ $pengukuran->kategori_bbu_badge }}">{{ $pengukuran->kategori_bbu }}</span></td></tr>
                    <tr><td class="text-muted">TB/U</td><td><span class="badge {{ $pengukuran->kategori_tbu_badge }}">{{ $pengukuran->kategori_tbu }}</span></td></tr>
                    <tr><td class="text-muted">BB/TB</td><td><span class="badge {{ $pengukuran->kategori_bbtb_badge }}">{{ $pengukuran->kategori_bbtb }}</span></td></tr>
                    <tr>
                        <td class="text-muted">Status</td>
                        <td><span class="badge {{ $pengukuran->status_badge_class }}">{{ $pengukuran->status_stunting }}</span></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('tanggalUkur').addEventListener('change', function () {
    const tglLahir = new Date(this.dataset.tglLahir);
    const tglUkur = new Date(this.value);
    const months = (tglUkur.getFullYear() - tglLahir.getFullYear()) * 12 + (tglUkur.getMonth() - tglLahir.getMonth());
    document.getElementById('umurDisplay').value = months >= 0 ? months : 0;
});
</script>
@endpush