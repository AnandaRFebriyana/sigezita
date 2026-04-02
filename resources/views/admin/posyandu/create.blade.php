@extends('layouts.app')
@section('title', 'Tambah Posyandu')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.posyandu.index') }}">Posyandu</a></li>
<li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="page-header">
    <h1><i class="fas fa-hospital mr-2 text-primary"></i> Tambah Posyandu</h1>
</div>

<div class="col-lg-8 px-0">
    <div class="card">
        <div class="card-header">Form Data Posyandu</div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.posyandu.store') }}">
                @csrf
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label class="form-label">Nama Posyandu <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                                value="{{ old('nama') }}" required>
                            @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Kelurahan</label>
                            <input type="text" name="kelurahan" class="form-control" value="{{ old('kelurahan') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Kecamatan</label>
                            <input type="text" name="kecamatan" class="form-control" value="{{ old('kecamatan') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Kota/Kabupaten</label>
                            <input type="text" name="kota" class="form-control" value="{{ old('kota') }}">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Alamat Lengkap</label>
                    <textarea name="alamat" class="form-control" rows="2">{{ old('alamat') }}</textarea>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Penanggung Jawab</label>
                            <input type="text" name="penanggung_jawab" class="form-control" value="{{ old('penanggung_jawab') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">No. HP Posyandu</label>
                            <input type="tel" name="no_hp" class="form-control" value="{{ old('no_hp') }}">
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end" style="gap:0.5rem">
                    <a href="{{ route('admin.posyandu.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection