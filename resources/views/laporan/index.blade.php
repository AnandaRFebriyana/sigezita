@extends('layouts.app')
@section('title', 'Laporan')
@section('breadcrumb')
<li class="breadcrumb-item active">Laporan</li>
@endsection

@section('content')
<div class="page-header">
    <h1><i class="fas fa-file-chart-bar mr-2 text-success"></i> Laporan Status Gizi Balita</h1>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><i class="fas fa-filter mr-2"></i> Parameter Laporan</div>
            <div class="card-body">
                <form method="POST" action="{{ route('laporan.generate') }}" id="formLaporan">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Posyandu</label>
                        <select name="posyandu_id" class="form-control">
                            <option value="">Semua Posyandu</option>
                            @foreach($posyanduList as $p)
                            <option value="{{ $p->id }}">{{ $p->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">Tanggal Dari <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_dari" class="form-control"
                                    value="{{ date('Y-m-01') }}" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">Tanggal Sampai <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_sampai" class="form-control"
                                    value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Format Output</label>
                        <div class="row">
                            <div class="col-6">
                                <div class="card border" style="cursor:pointer" onclick="selectFormat('html', this)">
                                    <div class="card-body text-center py-3">
                                        <i class="fas fa-eye fa-2x text-info mb-2 d-block"></i>
                                        <strong>Lihat di Browser</strong>
                                        <small class="d-block text-muted">HTML Preview</small>
                                        <input type="radio" name="format" value="html" id="fHtml" class="d-none" checked>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="card border" style="cursor:pointer" onclick="selectFormat('pdf', this)">
                                    <div class="card-body text-center py-3">
                                        <i class="fas fa-file-pdf fa-2x text-danger mb-2 d-block"></i>
                                        <strong>Cetak / PDF</strong>
                                        <small class="d-block text-muted">Printable View</small>
                                        <input type="radio" name="format" value="pdf" id="fPdf" class="d-none">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex" style="gap:0.5rem">
                        <button type="submit" class="btn btn-success flex-grow-1">
                            <i class="fas fa-chart-bar mr-1"></i> Buat Laporan
                        </button>
                    </div>
                </form>

                <hr>

                <!-- Excel Export -->
                <form method="POST" action="{{ route('laporan.export-excel') }}">
                    @csrf
                    <input type="hidden" name="posyandu_id" id="exportPosyandu">
                    <input type="hidden" name="tanggal_dari" id="exportDari">
                    <input type="hidden" name="tanggal_sampai" id="exportSampai">
                    <button type="submit" class="btn btn-outline-success btn-block"
                        onclick="syncExportFields()">
                        <i class="fas fa-file-excel mr-1"></i> Export Excel (CSV)
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card border-left-info">
            <div class="card-header"><i class="fas fa-info-circle mr-2 text-info"></i> Panduan Laporan</div>
            <div class="card-body" style="font-size:0.85rem">
                <p>Laporan status gizi mencakup data pengukuran balita berdasarkan rentang tanggal yang dipilih.</p>
                <ul class="pl-3">
                    <li class="mb-2"><strong>HTML Preview:</strong> Tampilkan laporan di browser, dapat dicetak manual</li>
                    <li class="mb-2"><strong>Cetak/PDF:</strong> Format siap cetak yang dioptimalkan untuk printer</li>
                    <li class="mb-2"><strong>Excel/CSV:</strong> Data mentah yang dapat diolah di spreadsheet</li>
                </ul>
                <div class="alert alert-light mb-0" style="font-size:0.8rem">
                    <i class="fas fa-lightbulb text-warning mr-1"></i>
                    Laporan menggunakan data pengukuran terbaru setiap balita. Kosongkan filter posyandu untuk laporan keseluruhan.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let selectedCard = document.querySelector('[onclick="selectFormat(\'html\', this)"]');
selectedCard?.classList.add('border-success');

function selectFormat(val, el) {
    document.getElementById(val === 'html' ? 'fHtml' : 'fPdf').checked = true;
    document.querySelectorAll('[onclick^="selectFormat"]').forEach(c => c.classList.remove('border-success','bg-light'));
    el.classList.add('border-success', 'bg-light');
}

function syncExportFields() {
    document.getElementById('exportPosyandu').value = document.querySelector('[name=posyandu_id]').value;
    document.getElementById('exportDari').value = document.querySelector('[name=tanggal_dari]').value;
    document.getElementById('exportSampai').value = document.querySelector('[name=tanggal_sampai]').value;
}
</script>
@endpush