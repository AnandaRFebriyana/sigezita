@extends('layouts.app')
@section('title', 'Edit Posyandu')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.posyandu.index') }}">Posyandu</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="page-header">
    <h1><i class="fas fa-edit mr-2 text-warning"></i> Edit Posyandu</h1>
</div>

<div class="col-lg-8 px-0">
    <div class="card">
        <div class="card-header">Edit: <strong>{{ $posyandu->nama }}</strong></div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.posyandu.update', $posyandu) }}">
                @csrf @method('PUT')

                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label class="form-label">Nama Posyandu <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control @error('nama_posyandu') is-invalid @enderror"
                                value="{{ old('nama', $posyandu->nama) }}" required>
                            @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Kelurahan</label>
                            <input type="text" name="kelurahan" class="form-control" value="{{ old('kelurahan', $posyandu->kelurahan) }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Kecamatan</label>
                            <input type="text" name="kecamatan" class="form-control" value="{{ old('kecamatan', $posyandu->kecamatan) }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Kota/Kabupaten</label>
                            <input type="text" name="kota" class="form-control" value="{{ old('kota', $posyandu->kota) }}">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Alamat Lengkap</label>
                    <textarea name="alamat" class="form-control" rows="2">{{ old('alamat', $posyandu->alamat) }}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Penanggung Jawab</label>
                            <input type="text" name="penanggung_jawab" class="form-control"
                                value="{{ old('penanggung_jawab', $posyandu->penanggung_jawab) }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">No. HP</label>
                            <input type="tel" name="no_hp" class="form-control" value="{{ old('no_hp', $posyandu->no_hp) }}">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Status</label>
                    <div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="is_active" value="1" id="aktif"
                                {{ old('is_active', $posyandu->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="aktif">Aktif</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="is_active" value="0" id="nonaktif"
                                {{ !old('is_active', $posyandu->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="nonaktif">Nonaktif</label>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end" style="gap:0.5rem">
                    <a href="{{ route('admin.posyandu.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save mr-1"></i> Update Posyandu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection