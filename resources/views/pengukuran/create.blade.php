@extends('layouts.app')
@section('title', 'Input Pengukuran')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('pengukuran.index') }}">Pengukuran</a></li>
<li class="breadcrumb-item active">Input Pengukuran</li>
@endsection

@section('content')
<div class="page-header">
    <h1><i class="fas fa-weight mr-2 text-success"></i> Input Pengukuran Antropometri</h1>
</div>

<div class="row">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header"><i class="fas fa-clipboard-list mr-2"></i> Form Pengukuran</div>
            <div class="card-body">
                <form method="POST" action="{{ route('pengukuran.store') }}" id="formPengukuran">
                    @csrf

                    <div class="form-group">
                        <label class="form-label">Pilih Balita <span class="text-danger">*</span></label>
                        <select name="balita_id" id="balitaSelect"
                            class="form-control @error('balita_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Balita --</option>
                            @foreach($listBalita as $b)
                            <option value="{{ $b->id }}"
                                data-tgl="{{ $b->tanggal_lahir->format('Y-m-d') }}"
                                data-jk="{{ $b->jenis_kelamin }}"
                                {{ (old('balita_id') == $b->id || ($balita && $balita->id == $b->id)) ? 'selected' : '' }}>
                                {{ $b->nama }} ({{ $b->kode_balita }}) - {{ $b->umur_bulan }}
                            </option>
                            @endforeach
                        </select>
                        @error('balita_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <!-- Info Balita Terpilih -->
                    <div id="infoBalita" class="alert alert-info d-none" style="font-size:0.85rem">
                        <div class="row">
                            <div class="col-6">
                                <strong id="infoNama">-</strong><br>
                                <small>JK: <span id="infoJK">-</span></small>
                            </div>
                            <div class="col-6 text-right">
                                <strong>Umur: <span id="infoUmur">-</span> bulan</strong><br>
                                <small>Tgl Lahir: <span id="infoTglLahir">-</span></small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Tanggal Pengukuran <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_ukur" id="tanggalUkur"
                                    class="form-control @error('tanggal_ukur') is-invalid @enderror"
                                    value="{{ old('tanggal_ukur', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}" required>
                                @error('tanggal_ukur')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Umur saat Pengukuran</label>
                                <div class="input-group">
                                    <input type="text" id="umurDisplay" class="form-control" readonly
                                        style="background:#f8f9fc" placeholder="Dihitung otomatis">
                                    <div class="input-group-append">
                                        <span class="input-group-text">bulan</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-weight text-primary mr-1"></i>
                                    Berat Badan (BB) <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="number" name="berat_badan" step="0.01" min="0.5" max="50"
                                        class="form-control @error('berat_badan') is-invalid @enderror"
                                        value="{{ old('berat_badan') }}" placeholder="0.00" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text">kg</span>
                                    </div>
                                </div>
                                @error('berat_badan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-ruler-vertical text-success mr-1"></i>
                                    Tinggi/Panjang Badan (TB) <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="number" name="tinggi_badan" step="0.1" min="30" max="150"
                                        class="form-control @error('tinggi_badan') is-invalid @enderror"
                                        value="{{ old('tinggi_badan') }}" placeholder="0.0" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text">cm</span>
                                    </div>
                                </div>
                                <small class="text-muted">Gunakan panjang badan (tidur) untuk usia &lt; 24 bulan</small>
                                @error('tinggi_badan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Catatan</label>
                        <textarea name="catatan" class="form-control" rows="2"
                            placeholder="Catatan tambahan (opsional)">{{ old('catatan') }}</textarea>
                    </div>

                    <!-- IMT Preview (opsional) -->
                    <div id="imtPreview" class="d-none">
                        <hr>
                        <small class="text-muted">
                            <i class="fas fa-calculator mr-1"></i>
                            IMT: <strong id="imtValue">-</strong> kg/m²
                        </small>
                    </div>

                    <div class="d-flex justify-content-end mt-3" style="gap:0.5rem">
                        <a href="{{ route('pengukuran.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-1"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-brain mr-1"></i> Proses & Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function hitungUmur() {
    const sel = document.getElementById('balitaSelect');
    const opt = sel.options[sel.selectedIndex];
    const tglUkur = document.getElementById('tanggalUkur').value;
    const tglLahir = opt?.dataset?.tgl;

    if (tglLahir && tglUkur) {
        const lahir = new Date(tglLahir);
        const ukur = new Date(tglUkur);
        const months = (ukur.getFullYear() - lahir.getFullYear()) * 12 + (ukur.getMonth() - lahir.getMonth());
        document.getElementById('umurDisplay').value = months >= 0 ? months : 0;

        // Info balita
        const info = document.getElementById('infoBalita');
        if (opt.value) {
            info.classList.remove('d-none');
            document.getElementById('infoNama').textContent = opt.text.split('(')[0].trim();
            document.getElementById('infoJK').textContent = opt.dataset.jk === 'L' ? 'Laki-laki' : 'Perempuan';
            document.getElementById('infoUmur').textContent = months;
            document.getElementById('infoTglLahir').textContent = tglLahir;
        } else {
            info.classList.add('d-none');
        }
    }
}

document.getElementById('balitaSelect').addEventListener('change', hitungUmur);
document.getElementById('tanggalUkur').addEventListener('change', hitungUmur);

// IMT Preview
function hitungIMT() {
    const bb = parseFloat(document.querySelector('[name=berat_badan]').value);
    const tb = parseFloat(document.querySelector('[name=tinggi_badan]').value);
    if (bb > 0 && tb > 0) {
        const imt = (bb / Math.pow(tb / 100, 2)).toFixed(1);
        document.getElementById('imtValue').textContent = imt;
        document.getElementById('imtPreview').classList.remove('d-none');
    }
}
document.querySelector('[name=berat_badan]').addEventListener('input', hitungIMT);
document.querySelector('[name=tinggi_badan]').addEventListener('input', hitungIMT);

// Init jika ada balita terpilih
window.addEventListener('load', hitungUmur);
</script>
@endpush