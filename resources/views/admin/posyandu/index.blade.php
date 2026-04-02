@extends('layouts.app')
@section('title', 'Manajemen Posyandu')
@section('breadcrumb')
<li class="breadcrumb-item active">Manajemen Posyandu</li>
@endsection

@section('content')
<div class="page-header">
    <h1><i class="fas fa-hospital mr-2 text-success"></i> Manajemen Posyandu</h1>
    <a href="{{ route('admin.posyandu.create') }}" class="btn btn-primary">
        <i class="fas fa-plus mr-1"></i> Tambah Posyandu
    </a>
</div>

<!-- Filter -->
<div class="card">
    <div class="card-body py-2">
        <form method="GET" class="form-inline" style="gap:0.5rem">
            <div class="input-group" style="width:280px">
                <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-search fa-xs"></i></span></div>
                <input type="text" name="search" class="form-control" placeholder="Cari nama..." value="{{ request('search') }}">
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-filter mr-1"></i> Cari</button>
            <a href="{{ route('admin.posyandu.index') }}" class="btn btn-outline-secondary"><i class="fas fa-times"></i></a>
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
                        <th>Nama Posyandu</th>
                        <th>Wilayah</th>
                        <th>Total Balita</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($posyandu as $i => $p)
                    <tr>
                        <td>{{ $posyandu->firstItem() + $i }}</td>

                        <td><strong>{{ $p->nama }}</strong></td>

                        <td>
                            <small>
                                {{ implode(', ', array_filter([$p->kelurahan, $p->kecamatan, $p->kabupaten])) ?: '-' }}
                            </small>
                        </td>

                        <td>-</td> {{-- karena tidak ada field penanggung_jawab --}}

                        <td>
                            <span class="badge badge-info">
                                {{ $p->balita_count ?? 0 }} balita
                            </span>
                        </td>

                        <td>
                            <span class="badge {{ $p->is_active ? 'badge-success' : 'badge-secondary' }}">
                                {{ $p->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>

                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.posyandu.edit', $p->id) }}" class="btn btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <button onclick="confirmDelete('{{ route('admin.posyandu.destroy', $p->id) }}')" class="btn btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            Tidak ada data posyandu
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($posyandu->hasPages())
    <div class="card-footer d-flex justify-content-between">
        <small class="text-muted">{{ $posyandu->firstItem() }}-{{ $posyandu->lastItem() }} dari {{ $posyandu->total() }}</small>
        {{ $posyandu->links() }}
    </div>
    @endif
</div>
<form id="deleteForm" method="POST" style="display:none">@csrf @method('DELETE')</form>
@endsection
@push('scripts')
<script>
function confirmDelete(url) {
    if(confirm('Nonaktifkan posyandu ini?')) {
        document.getElementById('deleteForm').action = url;
        document.getElementById('deleteForm').submit();
    }
}
</script>
@endpush