@extends('layouts.app')
@section('title', 'Tambah Balita')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('balita.index') }}">Data Balita</a></li>
<li class="breadcrumb-item active">Tambah Balita</li>
@endsection

@section('content')
<div class="page-header">
    <h1><i class="fas fa-user-plus mr-2 text-primary"></i> Tambah Data Balita</h1>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><i class="fas fa-form mr-2"></i> Formulir Data Balita</div>
            <div class="card-body">
                <form method="POST" action="{{ route('balita.store') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Nama Balita <span class="text-danger">*</span></label>
                                <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                                    value="{{ old('nama') }}" placeholder="Nama lengkap balita" required>
                                @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                                <select name="jenis_kelamin" class="form-control @error('jenis_kelamin') is-invalid @enderror" required>
                                    <option value="">Pilih jenis kelamin</option>
                                    <option value="L" {{ old('jenis_kelamin') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('jenis_kelamin') === 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('jenis_kelamin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_lahir" class="form-control @error('tanggal_lahir') is-invalid @enderror"
                                    value="{{ old('tanggal_lahir') }}" max="{{ date('Y-m-d') }}" required id="tanggalLahir">
                                @error('tanggal_lahir')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <small class="text-muted" id="umurInfo"></small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Posyandu <span class="text-danger">*</span></label>
                                <select name="posyandu_id" class="form-control @error('posyandu_id') is-invalid @enderror" required>
                                    <option value="">Pilih posyandu</option>
                                    @foreach($posyandu as $p)
                                    <option value="{{ $p->id }}" {{ old('posyandu_id') == $p->id ? 'selected' : '' }}>
                                        {{ $p->nama }} ({{ $p->kode }})
                                    </option>
                                    @endforeach
                                </select>
                                @error('posyandu_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <hr>
                    <h6 class="font-weight-bold text-secondary mb-3">Data Orang Tua / Wali</h6>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Nama Orang Tua <span class="text-danger">*</span></label>
                                <input type="text" name="nama_orang_tua" class="form-control @error('nama_orang_tua') is-invalid @enderror"
                                    value="{{ old('nama_orang_tua') }}" placeholder="Nama ayah/ibu/wali" required>
                                @error('nama_orang_tua')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Nomor HP</label>
                                <input type="tel" name="no_hp" class="form-control @error('no_hp') is-invalid @enderror"
                                    value="{{ old('no_hp') }}" placeholder="Contoh: 08123456789">
                                @error('no_hp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror"
                            rows="2" placeholder="Alamat lengkap">{{ old('alamat') }}</textarea>
                        @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="d-flex justify-content-end mt-3" style="gap:0.5rem">
                        <a href="{{ route('balita.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-1"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-left-info">
            <div class="card-header"><i class="fas fa-info-circle mr-2 text-info"></i> Petunjuk Pengisian</div>
            <div class="card-body" style="font-size:0.8rem">
                <ul class="pl-3 mb-0">
                    <li class="mb-2">Isi semua field yang bertanda <span class="text-danger">*</span> (wajib)</li>
                    <li class="mb-2">Umur balita akan dihitung otomatis dari tanggal lahir</li>
                    <li class="mb-2">Sistem hanya menerima balita berumur 0–60 bulan</li>
                    <li class="mb-2">Kode balita akan digenerate otomatis</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('tanggalLahir').addEventListener('change', function () {
    const tgl = new Date(this.value);
    const today = new Date();
    const months = (today.getFullYear() - tgl.getFullYear()) * 12 + (today.getMonth() - tgl.getMonth());
    if (months >= 0) {
        const years = Math.floor(months / 12);
        const remainMonths = months % 12;
        let text = `Umur: ${months} bulan`;
        if (years > 0) text += ` (${years} thn ${remainMonths} bln)`;
        document.getElementById('umurInfo').textContent = text;
    }
});
</script>
@endpush