@extends('layouts.app')
@section('title', 'Edit Petugas')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Manajemen Petugas</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="page-header">
    <h1><i class="fas fa-user-edit mr-2 text-warning"></i> Edit Akun Petugas</h1>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">Edit Data Petugas: <strong>{{ $user->name }}</strong></div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.users.update', $user) }}">
                    @csrf @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $user->name) }}" required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">No. HP</label>
                                <input type="tel" name="no_hp" class="form-control" value="{{ old('no_hp', $user->no_hp) }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $user->email) }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Password Baru <small class="text-muted">(kosongkan jika tidak diubah)</small></label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Min. 8 karakter">
                                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Konfirmasi Password Baru</label>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password baru">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Assign ke Posyandu</label>
                        <div class="row">
                            @foreach($posyandu as $p)
                            <div class="col-md-6">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="posyandu_ids[]"
                                        value="{{ $p->id }}" id="pos_{{ $p->id }}"
                                        {{ in_array($p->id, old('posyandu_ids', $selectedPosyandu)) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="pos_{{ $p->id }}">
                                        {{ $p->nama_posyandu }}
                                        <small class="text-muted d-block">{{ $p->kode }}</small>
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Status Akun</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="is_active" value="1" id="aktif"
                                    {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="aktif">Aktif</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="is_active" value="0" id="nonaktif"
                                    {{ !old('is_active', $user->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="nonaktif">Nonaktif</label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end" style="gap:0.5rem">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save mr-1"></i> Update Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">Info Akun</div>
            <div class="card-body" style="font-size:0.82rem">
                <table class="table table-sm table-borderless mb-0">
                    <tr><td class="text-muted">Dibuat</td><td>{{ $user->created_at->format('d/m/Y') }}</td></tr>
                    <tr><td class="text-muted">Diperbarui</td><td>{{ $user->updated_at->format('d/m/Y') }}</td></tr>
                    <tr><td class="text-muted">Role</td><td><span class="badge badge-info">{{ ucfirst($user->role) }}</span></td></tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection