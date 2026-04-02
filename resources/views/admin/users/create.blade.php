@extends('layouts.app')
@section('title', 'Tambah Petugas')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Manajemen Petugas</a></li>
<li class="breadcrumb-item active">Tambah Petugas</li>
@endsection

@section('content')
<div class="page-header">
    <h1><i class="fas fa-user-plus mr-2 text-primary"></i> Tambah Akun Petugas</h1>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">Form Data Petugas</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.users.store') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name') }}" required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">No. HP</label>
                                <input type="tel" name="no_hp" class="form-control" value="{{ old('no_hp') }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Password <span class="text-danger">*</span></label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Minimal 8 karakter" required>
                                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                                <input type="password" name="password_confirmation" class="form-control"
                                    placeholder="Ulangi password" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Pilih Posyandu</label>
                        <select name="posyandu_id" class="form-control">
                            <option value="">-- Pilih Posyandu --</option>
                            @foreach($posyandu as $p)
                                <option value="{{ $p->id }}" {{ old('posyandu_id') == $p->id ? 'selected' : '' }}>
                                    {{ $p->nama }}
                                </option>
                            @endforeach
                        </select>

                        @if($posyandu->isEmpty())
                            <small class="text-muted">
                                Belum ada posyandu.
                                <a href="{{ route('admin.posyandu.create') }}">Tambah dulu</a>
                            </small>
                        @endif
                    </div>

                    <div class="d-flex justify-content-end" style="gap:0.5rem">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Simpan Petugas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-left-info">
            <div class="card-header"><i class="fas fa-info-circle mr-2 text-info"></i> Informasi</div>
            <div class="card-body" style="font-size:0.82rem">
                <ul class="pl-3 mb-0">
                    <li class="mb-2">Satu petugas dapat bertugas di lebih dari satu posyandu</li>
                    <li class="mb-2">Password minimal 8 karakter</li>
                    <li class="mb-2">Petugas hanya dapat melihat data balita posyandu yang ditugaskan</li>
                    <li>Email digunakan sebagai username untuk login</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection