<?php

namespace App\Http\Controllers;

use App\Models\Balita;
use App\Models\Pengukuran;
use App\Services\GiziClassifierService;
use App\Services\ZscoreCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengukuranController extends Controller
{
    public function __construct(
        protected GiziClassifierService $classifier,
        protected ZscoreCalculator $zscore,       // ← tambah ini
    ) {}

    // ──────────────────────────────────────────────
    // INDEX
    // ──────────────────────────────────────────────
    public function index(Request $request)
    {
        $user  = Auth::user();

        $query = Pengukuran::with(['balita.posyandu', 'petugas'])
            ->orderByDesc('tanggal_ukur');

        if ($user->isPetugas()) {
            $query->whereHas('balita', function ($q) use ($user) {
                $q->where('posyandu_id', $user->posyandu_id);
            });
        }

        if ($request->filled('balita_id')) {
            $query->where('balita_id', $request->balita_id);
        }

        if ($request->filled('status_stunting')) {
            $query->where('status_stunting', $request->status_stunting);
        }

        if ($request->filled('bulan') && $request->filled('tahun')) {
            $query->whereMonth('tanggal_ukur', $request->bulan)
                  ->whereYear('tanggal_ukur', $request->tahun);
        }

        $pengukuran = $query->paginate(15);

        return view('pengukuran.index', compact('pengukuran'));
    }

    // ──────────────────────────────────────────────
    // CREATE
    // ──────────────────────────────────────────────
    public function create(Request $request)
    {
        $user     = Auth::user();
        $balitaId = $request->get('balita_id');

        $balita = $balitaId ? Balita::findOrFail($balitaId) : null;

        $queryBalita = Balita::where('is_active', true);

        if ($user->isPetugas()) {
            $queryBalita->where('posyandu_id', $user->posyandu_id);
        }

        $listBalita = $queryBalita->orderBy('nama')->get();

        return view('pengukuran.create', compact('balita', 'listBalita'));
    }

    // ──────────────────────────────────────────────
    // STORE
    // ──────────────────────────────────────────────
    public function store(Request $request)
    {
        $validated = $request->validate([
            'balita_id'    => 'required|exists:balita,id',
            'tanggal_ukur' => 'required|date|before_or_equal:today',
            'berat_badan'  => 'required|numeric|min:1|max:50',
            'tinggi_badan' => 'required|numeric|min:30|max:130',
            'catatan'      => 'nullable|string',
        ]);

        $balita    = Balita::findOrFail($validated['balita_id']);
        $umurBulan = (int) $balita->tanggal_lahir
            ->diffInMonths(\Carbon\Carbon::parse($validated['tanggal_ukur']));

        // Prediksi label dari Flask
        $hasil = $this->classifier->predict([
            'jenis_kelamin' => $balita->jenis_kelamin,
            'umur'          => $umurBulan,
            'berat_badan'   => $validated['berat_badan'],
            'tinggi_badan'  => $validated['tinggi_badan'],
        ]);

        Pengukuran::create([
            'balita_id'       => $balita->id,
            'tanggal_ukur'    => $validated['tanggal_ukur'],
            'berat_badan'     => $validated['berat_badan'],
            'tinggi_badan'    => $validated['tinggi_badan'],
            'umur_bulan'      => $umurBulan,
            'user_id'         => Auth::id(),
            'status_stunting' => $hasil['stunting_status'] ?? 'Tidak diketahui',
            'kategori_tbu'    => $hasil['predictions']['tbu']   ?? null,
            'kategori_bbu'    => $hasil['predictions']['bbu']   ?? null,
            'kategori_bbtb'   => $hasil['predictions']['bbtb']  ?? null,
            'catatan'         => $validated['catatan'] ?? null,
        ]);

        return redirect()
            ->route('pengukuran.index')
            ->with('success', 'Pengukuran berhasil disimpan');
    }

    // ──────────────────────────────────────────────
    // SHOW  ← ini yang diperbaiki
    // ──────────────────────────────────────────────
    public function show(Pengukuran $pengukuran)
    {
        $pengukuran->load(['balita.posyandu', 'petugas']);

        $balita = $pengukuran->balita;

        // Hitung z-score
        $zscore = $this->zscore->hitungSemua(
            beratBadan:   (float) $pengukuran->berat_badan,
            tinggiBadan:  (float) $pengukuran->tinggi_badan,
            umurBulan:    (int)   $pengukuran->umur_bulan,
            jenisKelamin: $balita->jenis_kelamin,   // 'L' atau 'P'
        );

        return view('pengukuran.show', [
            'pengukuran'    => $pengukuran,
            'zscore_bbu'    => $zscore['zscore_bbu'],
            'zscore_tbu'    => $zscore['zscore_tbu'],
            'zscore_bbtb'   => $zscore['zscore_bbtb'],
            'kategori_bbu'  => $zscore['kategori_bbu'],
            'kategori_tbu'  => $zscore['kategori_tbu'],
            'kategori_bbtb' => $zscore['kategori_bbtb'],
        ]);
    }

    // ──────────────────────────────────────────────
    // EDIT
    // ──────────────────────────────────────────────
    public function edit(Pengukuran $pengukuran)
    {
        $pengukuran->load('balita');
        return view('pengukuran.edit', compact('pengukuran'));
    }

    // ──────────────────────────────────────────────
    // UPDATE
    // ──────────────────────────────────────────────
    public function update(Request $request, Pengukuran $pengukuran)
    {
        $validated = $request->validate([
            'tanggal_ukur' => 'required|date|before_or_equal:today',
            'berat_badan'  => 'required|numeric|min:1|max:50',
            'tinggi_badan' => 'required|numeric|min:30|max:130',
            'catatan'      => 'nullable|string',
        ]);

        $balita    = $pengukuran->balita;
        $umurBulan = (int) $balita->tanggal_lahir
            ->diffInMonths(\Carbon\Carbon::parse($validated['tanggal_ukur']));

        $hasil = $this->classifier->predict([
            'jenis_kelamin' => $balita->jenis_kelamin,
            'umur'          => $umurBulan,
            'berat_badan'   => $validated['berat_badan'],
            'tinggi_badan'  => $validated['tinggi_badan'],
        ]);

        $pengukuran->update([
            'tanggal_ukur'    => $validated['tanggal_ukur'],
            'berat_badan'     => $validated['berat_badan'],
            'tinggi_badan'    => $validated['tinggi_badan'],
            'umur_bulan'      => $umurBulan,
            'status_stunting' => $hasil['stunting_status'] ?? 'Tidak diketahui',
            'kategori_tbu'    => $hasil['predictions']['tbu']  ?? null,
            'kategori_bbu'    => $hasil['predictions']['bbu']  ?? null,
            'kategori_bbtb'   => $hasil['predictions']['bbtb'] ?? null,
            'catatan'         => $validated['catatan'],
        ]);

        return redirect()
            ->route('pengukuran.index')
            ->with('success', 'Pengukuran berhasil diperbarui');
    }

    // ──────────────────────────────────────────────
    // DESTROY
    // ──────────────────────────────────────────────
    public function destroy(Pengukuran $pengukuran)
    {
        $pengukuran->delete();

        return redirect()
            ->route('pengukuran.index')
            ->with('success', 'Data berhasil dihapus');
    }
}