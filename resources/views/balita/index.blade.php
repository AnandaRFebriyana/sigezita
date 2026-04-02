@extends('layouts.app')
@section('title', 'Data Balita')
@section('breadcrumb')
<li class="breadcrumb-item active">Data Balita</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1><i class="fas fa-child mr-2 text-primary"></i> Data Balita</h1>
        <small class="text-muted">Daftar seluruh balita yang terdaftar</small>
    </div>
    <div class="d-flex" style="gap:0.5rem">
        <a href="{{ route('balita.import.template') }}" class="btn btn-outline-success">
            <i class="fas fa-file-excel mr-1"></i> Download Template
        </a>
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalImport">
            <i class="fas fa-file-import mr-1"></i> Import Excel
        </button>
        <a href="{{ route('balita.create') }}" class="btn btn-primary">
            <i class="fas fa-plus mr-1"></i> Tambah Balita
        </a>
    </div>
</div>

{{-- Alert sukses import --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
    <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>
@endif

{{-- Alert error import (baris yang dilewati) --}}
@if(session('import_errors'))
<div class="alert alert-warning alert-dismissible fade show">
    <div class="d-flex align-items-start">
        <i class="fas fa-exclamation-triangle mr-2 mt-1"></i>
        <div>
            <strong>Beberapa baris dilewati karena ada error:</strong>
            <ul class="mb-0 mt-1" style="font-size:0.85rem">
                @foreach(session('import_errors') as $err)
                <li>Baris {{ $err['baris'] }}: {{ implode(', ', $err['pesan']) }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>
@endif

<!-- Filter Card -->
<div class="card">
    <div class="card-body py-2">
        <form method="GET" class="form-inline flex-wrap" style="gap:0.5rem">
            <div class="input-group" style="width:250px">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-search fa-xs"></i></span>
                </div>
                <input type="text" name="search" class="form-control" placeholder="Cari nama, kode, orang tua..."
                    value="{{ request('search') }}">
            </div>

            @if(auth()->user()->isAdmin())
            <select name="posyandu_id" class="form-control" style="width:200px">
                <option value="">Semua Posyandu</option>
                @foreach($posyandu as $p)
                <option value="{{ $p->id }}" {{ request('posyandu_id') == $p->id ? 'selected' : '' }}>
                    {{ $p->nama }}
                </option>
                @endforeach
            </select>
            @endif

            <select name="jenis_kelamin" class="form-control" style="width:150px">
                <option value="">Semua JK</option>
                <option value="L" {{ request('jenis_kelamin') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                <option value="P" {{ request('jenis_kelamin') === 'P' ? 'selected' : '' }}>Perempuan</option>
            </select>

            <button type="submit" class="btn btn-primary"><i class="fas fa-filter mr-1"></i> Filter</button>
            <a href="{{ route('balita.index') }}" class="btn btn-outline-secondary"><i class="fas fa-times mr-1"></i> Reset</a>
        </form>
    </div>
</div>

<!-- Table -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kode</th>
                        <th>Nama Balita</th>
                        <th>JK</th>
                        <th>Tgl Lahir</th>
                        <th>Umur</th>
                        <th>Orang Tua</th>
                        <th>Posyandu</th>
                        <th>Status Terakhir</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($balita as $i => $b)
                    <tr>
                        <td>{{ $balita->firstItem() + $i }}</td>
                        <td><code>{{ $b->kode_balita }}</code></td>
                        <td>
                            <a href="{{ route('balita.show', $b) }}" class="font-weight-bold text-dark">
                                {{ $b->nama }}
                            </a>
                        </td>
                        <td>
                            <span class="badge {{ $b->jenis_kelamin === 'L' ? 'badge-info' : 'badge-secondary' }}">
                                <i class="fas fa-{{ $b->jenis_kelamin === 'L' ? 'mars' : 'venus' }} mr-1"></i>
                                {{ $b->jenis_kelamin === 'L' ? 'L' : 'P' }}
                            </span>
                        </td>
                        <td>{{ $b->tanggal_lahir->format('d/m/Y') }}</td>
                        <td><span class="font-weight-bold">{{ $b->umur_bulan }}</span></td>
                        <td>{{ $b->nama_orang_tua }}</td>
                        <td><small>{{ $b->posyandu->nama ?? '-' }}</small></td>
                        <td>
                            @if($b->latestPengukuran)
                                <span class="badge badge-success">
                                    {{ $b->latestPengukuran->status_stunting ?? '-' }}
                                </span>
                            @else
                            <span class="badge badge-secondary">Belum diukur</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('balita.show', $b) }}" class="btn btn-info" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('pengukuran.create', ['balita_id' => $b->id]) }}" class="btn btn-success" title="Input Pengukuran">
                                    <i class="fas fa-weight"></i>
                                </a>
                                <a href="{{ route('balita.edit', $b) }}" class="btn btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button onclick="confirmDelete('{{ route('balita.destroy', $b) }}')" class="btn btn-danger" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-4 text-muted">
                            <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                            Tidak ada data balita ditemukan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($balita->hasPages())
    <div class="card-footer d-flex justify-content-between align-items-center">
        <small class="text-muted">Menampilkan {{ $balita->firstItem() }}-{{ $balita->lastItem() }} dari {{ $balita->total() }} data</small>
        {{ $balita->links() }}
    </div>
    @endif
</div>

<!-- ══════════════════════════════════════════════════════ -->
<!-- MODAL IMPORT EXCEL                                      -->
<!-- ══════════════════════════════════════════════════════ -->
<div class="modal fade" id="modalImport" tabindex="-1" role="dialog" aria-labelledby="modalImportLabel">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="modalImportLabel">
                    <i class="fas fa-file-import mr-2 text-success"></i> Import Data Balita dari Excel
                </h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">

                {{-- ── STEP 1: Pilih file ── --}}
                <div id="stepUpload">
                    <div class="row align-items-center mb-3">
                        <div class="col-md-8">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="importFile" accept=".xlsx,.xls">
                                <label class="custom-file-label" for="importFile">Pilih file Excel (.xlsx)</label>
                            </div>
                            <small class="text-muted">Maksimal 5 MB. Gunakan template yang sudah disediakan.</small>
                        </div>
                        <div class="col-md-4 mt-2 mt-md-0">
                            <button type="button" class="btn btn-primary btn-block" id="btnPreview" disabled>
                                <i class="fas fa-eye mr-1"></i> Preview Data
                            </button>
                        </div>
                    </div>

                    {{-- Info template --}}
                    <div class="alert alert-info py-2 mb-0">
                        <i class="fas fa-info-circle mr-1"></i>
                        Belum punya template?
                        <a href="{{ route('balita.import.template') }}" class="alert-link">Download di sini</a>.
                        Kolom wajib: <code>nama, tanggal_lahir, jenis_kelamin, nama_orang_tua, kode</code>.
                    </div>
                </div>

                {{-- ── STEP 2: Preview hasil ── --}}
                <div id="stepPreview" style="display:none">

                    {{-- Ringkasan --}}
                    <div id="importSummary" class="row mb-3"></div>

                    {{-- Tabel error (jika ada) --}}
                    <div id="importErrors" style="display:none" class="mb-3">
                        <div class="alert alert-danger py-2">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            <strong>Baris berikut mengandung error dan akan dilewati saat import:</strong>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered" id="errorTable">
                                <thead class="thead-danger">
                                    <tr>
                                        <th>Baris</th>
                                        <th>Error</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Tabel preview data --}}
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered table-hover" id="previewTable">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Status</th>
                                    <th>Nama</th>
                                    <th>Tgl Lahir</th>
                                    <th>JK</th>
                                    <th>Orang Tua</th>
                                    <th>No. HP</th>
                                    <th>Posyandu</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>

                    <button type="button" class="btn btn-outline-secondary btn-sm mt-2" id="btnBackUpload">
                        <i class="fas fa-arrow-left mr-1"></i> Ganti File
                    </button>
                </div>

                {{-- Spinner loading --}}
                <div id="importLoading" style="display:none" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status"></div>
                    <div class="mt-2 text-muted">Membaca file, mohon tunggu...</div>
                </div>

            </div>{{-- /modal-body --}}

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> Tutup
                </button>
                {{-- Tombol simpan — submit form nyata --}}
                <form id="importForm" action="{{ route('balita.import.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="file" id="importFileHidden" style="display:none">
                    <button type="submit" class="btn btn-success" id="btnImport" style="display:none">
                        <i class="fas fa-save mr-1"></i> Simpan <span id="btnImportCount"></span> Data Valid
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>

<!-- Delete Modal -->
<form id="deleteForm" method="POST" style="display:none">
    @csrf @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
// ── Delete ──────────────────────────────────────────────────────
function confirmDelete(url) {
    if (confirm('Yakin ingin menghapus data balita ini?')) {
        document.getElementById('deleteForm').action = url;
        document.getElementById('deleteForm').submit();
    }
}

// ── Import modal logic ───────────────────────────────────────────
(function () {
    const fileInput   = document.getElementById('importFile');
    const btnPreview  = document.getElementById('btnPreview');
    const btnBackUpload = document.getElementById('btnBackUpload');
    const btnImport   = document.getElementById('btnImport');
    const btnImportCount = document.getElementById('btnImportCount');
    const stepUpload  = document.getElementById('stepUpload');
    const stepPreview = document.getElementById('stepPreview');
    const loading     = document.getElementById('importLoading');
    const summary     = document.getElementById('importSummary');
    const errorsDiv   = document.getElementById('importErrors');
    const errorTbody  = document.querySelector('#errorTable tbody');
    const previewTbody = document.querySelector('#previewTable tbody');
    const importForm  = document.getElementById('importForm');
    const importFileHidden = document.getElementById('importFileHidden');

    // Update label nama file
    fileInput.addEventListener('change', function () {
        const label = this.nextElementSibling;
        label.textContent = this.files.length ? this.files[0].name : 'Pilih file Excel (.xlsx)';
        btnPreview.disabled = !this.files.length;
        // Reset step
        showStep('upload');
        btnImport.style.display = 'none';
    });

    // Preview
    btnPreview.addEventListener('click', function () {
        if (!fileInput.files.length) return;

        showStep('loading');

        const fd = new FormData();
        fd.append('file', fileInput.files[0]);
        fd.append('_token', '{{ csrf_token() }}');

        fetch('{{ route("balita.import.preview") }}', {
            method: 'POST',
            body: fd,
        })
        .then(r => r.json())
        .then(data => {
            if (data.error) {
                alert('Error: ' + data.error);
                showStep('upload');
                return;
            }
            renderPreview(data);
            showStep('preview');

            // Salin file ke hidden input untuk submit nyata
            // (file input tidak bisa di-set secara programatis, kita pakai DataTransfer trick)
            try {
                const dt = new DataTransfer();
                dt.items.add(fileInput.files[0]);
                importFileHidden.files = dt.files;
            } catch (e) {
                // fallback: re-attach original input ke form (jika browser tidak support DataTransfer)
                importFileHidden.parentNode.replaceChild(fileInput.cloneNode(), importFileHidden);
            }

            if (data.valid > 0) {
                btnImportCount.textContent = '(' + data.valid + ')';
                btnImport.style.display = 'inline-block';
            } else {
                btnImport.style.display = 'none';
            }
        })
        .catch(err => {
            alert('Terjadi kesalahan jaringan. Coba lagi.');
            showStep('upload');
        });
    });

    btnBackUpload.addEventListener('click', function () {
        showStep('upload');
        btnImport.style.display = 'none';
    });

    // Reset modal saat ditutup
    document.getElementById('modalImport').addEventListener('hidden.bs.modal', function () {
        fileInput.value = '';
        fileInput.nextElementSibling.textContent = 'Pilih file Excel (.xlsx)';
        btnPreview.disabled = true;
        btnImport.style.display = 'none';
        showStep('upload');
    });

    // ── Helper functions ────────────────────────────────────────
    function showStep(step) {
        stepUpload.style.display  = step === 'upload'  ? '' : 'none';
        stepPreview.style.display = step === 'preview' ? '' : 'none';
        loading.style.display     = step === 'loading' ? '' : 'none';
    }

    function renderPreview(data) {
        // Ringkasan
        summary.innerHTML = `
            <div class="col-sm-4">
                <div class="alert alert-secondary py-2 text-center mb-0">
                    <div style="font-size:1.4rem;font-weight:700">${data.total}</div>
                    <small>Total Baris</small>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="alert alert-success py-2 text-center mb-0">
                    <div style="font-size:1.4rem;font-weight:700">${data.valid}</div>
                    <small>Data Valid</small>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="alert ${data.error_count > 0 ? 'alert-danger' : 'alert-success'} py-2 text-center mb-0">
                    <div style="font-size:1.4rem;font-weight:700">${data.error_count}</div>
                    <small>Baris Error</small>
                </div>
            </div>
        `;

        // Tabel error
        if (data.errors.length > 0) {
            errorTbody.innerHTML = data.errors.map(e =>
                `<tr>
                    <td class="text-center">${e.baris}</td>
                    <td>${e.pesan.map(p => `<span class="badge badge-danger mr-1">${p}</span>`).join('')}</td>
                </tr>`
            ).join('');
            errorsDiv.style.display = '';
        } else {
            errorsDiv.style.display = 'none';
        }

        // Tabel preview data
        previewTbody.innerHTML = data.rows.map(row => {
            const isValid = row.status === 'valid';
            const rowClass = isValid ? '' : 'table-danger';
            const badge = isValid
                ? '<span class="badge badge-success">Valid</span>'
                : '<span class="badge badge-danger">Error</span>';
            return `<tr class="${rowClass}">
                <td class="text-center">${row.no}</td>
                <td>${badge}</td>
                <td>${esc(row.nama)}</td>
                <td>${esc(row.tanggal_lahir)}</td>
                <td>
                    <span class="badge ${row.jenis_kelamin === 'L' ? 'badge-info' : 'badge-secondary'}">
                        ${row.jenis_kelamin === 'L' ? '♂ L' : '♀ P'}
                    </span>
                </td>
                <td>${esc(row.nama_orang_tua)}</td>
                <td>${esc(row.no_telepon)}</td>
                <td>${esc(row.nama_posyandu)}</td>
            </tr>`;
        }).join('');
    }

    function esc(str) {
        if (!str) return '<span class="text-muted">—</span>';
        return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
    }
})();
</script>
@endpush