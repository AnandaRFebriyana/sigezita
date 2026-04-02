@extends('layouts.app')
@section('title', 'Manajemen Petugas')
@section('breadcrumb')
<li class="breadcrumb-item active">Manajemen Petugas</li>
@endsection

@section('content')
<div class="page-header">
    <h1><i class="fas fa-users-cog mr-2 text-primary"></i> Manajemen Petugas</h1>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
        <i class="fas fa-user-plus mr-1"></i> Tambah Petugas
    </a>
</div>

<!-- Filter -->
<div class="card">
    <div class="card-body py-2">
        <form method="GET" class="form-inline" style="gap:0.5rem">
            <div class="input-group" style="width:280px">
                <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-search fa-xs"></i></span></div>
                <input type="text" name="search" class="form-control" placeholder="Cari nama/email..." value="{{ request('search') }}">
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-filter mr-1"></i> Cari</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary"><i class="fas fa-times"></i></a>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>No. HP</th>
                        <th>Posyandu</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $i => $u)
                    <tr>
                        <td>{{ $users->firstItem() + $i }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar mr-2" style="width:2rem;height:2rem;font-size:0.85rem;background:#4e73df">
                                    {{ strtoupper(substr($u->name, 0, 1)) }}
                                </div>
                                <strong>{{ $u->name }}</strong>
                            </div>
                        </td>
                        <td>{{ $u->email }}</td>
                        <td>{{ $u->phone ?? '-' }}</td>
                        <td>
                            @if($u->posyandu)
                                <span class="badge badge-light" style="font-size:0.75rem">
                                    {{ $u->posyandu->nama }}
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $u->is_active ? 'badge-success' : 'badge-secondary' }}">
                                {{ $u->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.users.edit', $u) }}" class="btn btn-warning"><i class="fas fa-edit"></i></a>
                                <button onclick="confirmDelete('{{ route('admin.users.destroy', $u) }}')" class="btn btn-danger"><i class="fas fa-user-slash"></i></button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">Tidak ada data petugas</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($users->hasPages())
    <div class="card-footer d-flex justify-content-between">
        <small class="text-muted">{{ $users->firstItem() }}-{{ $users->lastItem() }} dari {{ $users->total() }}</small>
        {{ $users->links() }}
    </div>
    @endif
</div>
<form id="deleteForm" method="POST" style="display:none">@csrf @method('DELETE')</form>
@endsection
@push('scripts')
<script>
function confirmDelete(url) {
    if(confirm('Nonaktifkan akun petugas ini?')) {
        document.getElementById('deleteForm').action = url;
        document.getElementById('deleteForm').submit();
    }
}
</script>
@endpush