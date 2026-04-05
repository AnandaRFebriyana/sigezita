@extends('layouts.app')
@section('title', 'Edit Balita')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('balita.index') }}">Data Balita</a></li>
<li class="breadcrumb-item"><a href="{{ route('balita.show', $balita) }}">{{ $balita->nama_balita }}</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="page-header">
    <h1><i class="fas fa-edit mr-2 text-warning"></i> Edit Data Balita</h1>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                Edit: <strong>{{ $balita->nama_balita }}</strong>
                <code class="ml-2">{{ $balita->kode_balita }}</code>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('balita.update', $balita) }}">
                    @csrf @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Nama Balita <span class="text-danger">*</span></label>
                                <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                                    value="{{ old('nama', $balita->nama) }}" required>
                                @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                                <select name="jenis_kelamin" class="form-control" required>
                                    <option value="L" {{ old('jenis_kelamin', $balita->jenis_kelamin) === 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('jenis_kelamin', $balita->jenis_kelamin) === 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_lahir" class="form-control @error('tanggal_lahir') is-invalid @enderror"
                                    value="{{ old('tanggal_lahir', $balita->tanggal_lahir->format('Y-m-d')) }}"
                                    max="{{ date('Y-m-d') }}" required>
                                @error('tanggal_lahir')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Posyandu <span class="text-danger">*</span></label>
                                <select name="posyandu_id" class="form-control" required>
                                    @foreach($posyandu as $p)
                                    <option value="{{ $p->id }}" {{ old('posyandu_id', $balita->posyandu_id) == $p->id ? 'selected' : '' }}>
                                        {{ $p->nama }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Nama Orang Tua <span class="text-danger">*</span></label>
                                <input type="text" name="nama_orang_tua" class="form-control"
                                    value="{{ old('nama_orang_tua', $balita->nama_orang_tua) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">No. HP</label>
                                <input type="tel" name="no_hp" class="form-control" value="{{ old('no_hp', $balita->no_hp) }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat" class="form-control" rows="2">{{ old('alamat', $balita->alamat) }}</textarea>
                    </div>

                    <div class="d-flex justify-content-end" style="gap:0.5rem">
                        <a href="{{ route('balita.show', $balita) }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save mr-1"></i> Update Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-left-warning">
            <div class="card-header"><i class="fas fa-exclamation-triangle mr-1 text-warning"></i> Perhatian</div>
            <div class="card-body" style="font-size:0.82rem">
                <p>Mengubah data dasar balita (terutama tanggal lahir dan jenis kelamin) dapat mempengaruhi interpretasi riwayat pengukuran sebelumnya.</p>
                <p class="mb-0">Pastikan data yang diubah sudah benar sebelum menyimpan.</p>
            </div>
        </div>
    </div>
</div>
@endsection