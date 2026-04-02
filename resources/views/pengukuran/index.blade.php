@extends('layouts.app')
@section('title', 'Data Pengukuran')
@section('breadcrumb')
<li class="breadcrumb-item active">Pengukuran</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1><i class="fas fa-weight mr-2 text-info"></i> Data Pengukuran Antropometri</h1>
        <small class="text-muted">Riwayat seluruh pengukuran balita</small>
    </div>
    <a href="{{ route('pengukuran.create') }}" class="btn btn-success">
        <i class="fas fa-plus mr-1"></i> Input Pengukuran
    </a>
</div>

<!-- Filter -->
<div class="card">
    <div class="card-body py-2">
        <form method="GET" class="form-inline flex-wrap" style="gap:0.5rem">
            <div class="input-group" style="width:230px">
                <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-search fa-xs"></i></span></div>
                <input type="text" name="search" class="form-control" placeholder="Cari nama balita..." value="{{ request('search') }}">
            </div>
            <select name="status" class="form-control" style="width:200px">
                <option value="">Semua Status</option>
                <option value="Normal" {{ request('status') === 'Normal' ? 'selected' : '' }}>Normal</option>
                <option value="Berisiko Gangguan Pertumbuhan" {{ request('status') === 'Berisiko Gangguan Pertumbuhan' ? 'selected' : '' }}>Berisiko</option>
                <option value="Stunting" {{ request('status') === 'Stunting' ? 'selected' : '' }}>Stunting</option>
            </select>
            <input type="date" name="tanggal_dari" class="form-control" value="{{ request('tanggal_dari') }}" style="width:160px">
            <span class="text-muted">s/d</span>
            <input type="date" name="tanggal_sampai" class="form-control" value="{{ request('tanggal_sampai') }}" style="width:160px">
            <button type="submit" class="btn btn-primary"><i class="fas fa-filter mr-1"></i> Filter</button>
            <a href="{{ route('pengukuran.index') }}" class="btn btn-outline-secondary"><i class="fas fa-times"></i></a>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" style="font-size:0.83rem">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tanggal</th>
                        <th>Balita</th>
                        <th>Umur</th>
                        <th>BB (kg)</th>
                        <th>TB (cm)</th>
                        <th>BB/U</th>
                        <th>TB/U</th>
                        <th>BB/TB</th>
                        <th>Status</th>
                        <th>Petugas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengukuran as $i => $p)
                    <tr>
                        <td>{{ $pengukuran->firstItem() + $i }}</td>
                        <td>{{ $p->tanggal_ukur->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('balita.show', $p->balita) }}" class="font-weight-bold text-dark">{{ $p->balita->nama }}</a>
                            <small class="d-block text-muted">{{ $p->balita->kode_balita }}</small>
                        </td>
                        <td>{{ $p->umur_bulan }} bln</td>
                        <td>{{ $p->berat_badan }}</td>
                        <td>{{ $p->tinggi_badan }}</td>
                        <td><span class="badge {{ $p->kategori_bbu_badge }}" style="font-size:0.7rem">{{ $p->kategori_bbu }}</span></td>
                        <td><span class="badge {{ $p->kategori_tbu_badge }}" style="font-size:0.7rem">{{ $p->kategori_tbu }}</span></td>
                        <td><span class="badge {{ $p->kategori_bbtb_badge }}" style="font-size:0.7rem">{{ $p->kategori_bbtb }}</span></td>
                        <td><span class="badge {{ $p->status_badge_class }}">{{ $p->status_stunting }}</span></td>
                        <td><small>{{ $p->petugas->name ?? '-' }}</small></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('pengukuran.show', $p) }}" class="btn btn-info"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('pengukuran.edit', $p) }}" class="btn btn-warning"><i class="fas fa-edit"></i></a>
                                <button onclick="confirmDelete('{{ route('pengukuran.destroy', $p) }}')" class="btn btn-danger"><i class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="12" class="text-center text-muted py-4"><i class="fas fa-inbox fa-2x d-block mb-2"></i>Tidak ada data pengukuran</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($pengukuran->hasPages())
    <div class="card-footer d-flex justify-content-between align-items-center">
        <small class="text-muted">{{ $pengukuran->firstItem() }}-{{ $pengukuran->lastItem() }} dari {{ $pengukuran->total() }}</small>
        {{ $pengukuran->links() }}
    </div>
    @endif
</div>
<form id="deleteForm" method="POST" style="display:none">@csrf @method('DELETE')</form>
@endsection
@push('scripts')
<script>
function confirmDelete(url) {
    if(confirm('Hapus data pengukuran ini?')) {
        document.getElementById('deleteForm').action = url;
        document.getElementById('deleteForm').submit();
    }
}
</script>
@endpush