<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Balita;
use App\Models\Posyandu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BalitaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user  = Auth::user();
        $query = Balita::with(['posyandu', 'latestPengukuran'])
            ->active();

        if ($user->isPetugas()) {
            $query->byPosyandu($user->posyandu_id);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('nama', 'like', "%{$s}%")
                  ->orWhere('kode_balita', 'like', "%{$s}%")
                  ->orWhere('nama_orang_tua', 'like', "%{$s}%");
            });
        }

        if ($request->filled('posyandu_id')) {
            $query->where('posyandu_id', $request->posyandu_id);
        }
 
        if ($request->filled('jenis_kelamin')) {
            $query->where('jenis_kelamin', $request->jenis_kelamin);
        }

        $umur = Carbon::parse($request->tanggal_lahir)->diffInMonths(now());

        if ($umur > 60) {
            return back()->withErrors([
                'tanggal_lahir' => 'Umur balita maksimal 60 bulan'
            ])->withInput();
        }
 
        $balita = $query->orderBy('nama')
            ->paginate($request->get('per_page', 15));

        $posyandu = Posyandu::where('is_active', true)->get();
        
        return view('balita.index', compact('balita', 'posyandu'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        $posyandu = $user->isAdmin()
            ? Posyandu::where('is_active', true)->get()
            : $user->posyandu;
 
        return view('balita.create', compact('posyandu'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'           => 'required|string|max:100',
            'jenis_kelamin'  => 'required|in:L,P',
            'tanggal_lahir'  => 'required|date|before:today',
            'nama_orang_tua' => 'required|string|max:100',
            'no_hp'          => 'nullable|string|max:15',
            'alamat'         => 'nullable|string',
            'posyandu_id'    => 'required|exists:posyandu,id',
        ]);

        
        $validated['kode_balita'] = Balita::generateKode();
        $validated['created_by'] = Auth::id();

        Balita::create($validated);

        return redirect()->route('balita.index')
            ->with('success', 'Data balita berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Balita $balita)
    {
        $this->authorizeBalita($balita);
        $balita->load(['posyandu', 'creator', 'pengukuran']);
 
        $pengukuranData = $balita->pengukuran->map(fn($p) => [
            'tanggal' => $p->tanggal_ukur->format('d/m/Y'),
            'umur'    => $p->umur_bulan,
            'bb'      => $p->berat_badan,
            'tb'      => $p->tinggi_badan,
            'status'  => $p->status_stunting,
            'zscore_bbu'  => $p->zscore_bbu,
            'zscore_tbu'  => $p->zscore_tbu,
            'zscore_bbtb' => $p->zscore_bbtb,
        ]);
 
        return view('balita.show', compact('balita', 'pengukuranData'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Balita $balita)
    {
        $this->authorizeBalita($balita);
        $user = Auth::user();
        $posyandu = $user->isAdmin()
            ? Posyandu::where('is_active', true)->get()
            : $user->posyandu;
 
        return view('balita.edit', compact('balita', 'posyandu'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Balita $balita)
    {
        $this->authorizeBalita($balita);

        $validated = $request->validate([
            'nama'           => 'required|string|max:100',
            'jenis_kelamin'  => 'required|in:L,P',
            'tanggal_lahir'  => 'required|date|before:today',
            'nama_orang_tua' => 'required|string|max:100',
            'no_hp'          => 'nullable|string|max:15',
            'alamat'         => 'nullable|string',
            'posyandu_id'    => 'required|exists:posyandu,id',
        ]);

        $balita->update($validated);

        return redirect()->route('balita.show', $balita)
            ->with('success', 'Data balita berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Balita $balita)
    {
        $this->authorizeBalita($balita);
        $balita->update(['is_active' => false]);
 
        return redirect()->route('balita.index')
            ->with('success', 'Data balita berhasil dihapus.');
    }

    public function growthChart(Balita $balita)
    {
        $this->authorizeBalita($balita);

        $pengukuran = $balita->pengukuran()
            ->orderBy('tanggal_ukur')
            ->get(['tanggal_ukur','umur_bulan','berat_badan','tinggi_badan',
                   'kategori_bbu','kategori_tbu','kategori_bbtb','status_stunting']);

        return response()->json([
            'balita'      => $balita->only(['id','nama','jenis_kelamin','tanggal_lahir']),
            'pengukuran' => $pengukuran,
        ]);
    }

    protected function authorizeBalita(Balita $balita): void
    {
        $user = Auth::user();
        if ($user->isPetugas()) {
            $posyanduIds = $user->posyandu->pluck('id');
            abort_unless($posyanduIds->contains($balita->posyandu_id), 403, 'Akses ditolak.');
        }
    }
}
