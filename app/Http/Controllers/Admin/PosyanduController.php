<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Posyandu;
use App\Models\User;
use Illuminate\Http\Request;

class PosyanduController extends Controller
{
    public function index(Request $request)
    {
        $query = Posyandu::withCount('balita');

        if ($request->filled('search')) {
            $query->where('nama', 'like', "%{$request->search}%")
                  ->orWhere('kode', 'like', "%{$request->search}%");
        }

        $posyandu = $query->orderBy('nama')->paginate(10)->withQueryString();
        return view('admin.posyandu.index', compact('posyandu'));
    }

    public function create()
    {
        return view('admin.posyandu.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'    => 'required|string|max:100',
            'kelurahan'        => 'nullable|string|max:100',
            'kecamatan'        => 'nullable|string|max:100',
            'kota'             => 'nullable|string|max:100',
            'alamat'           => 'nullable|string',
        ]);
        $validated['kode'] = 'PSY-' . strtoupper(uniqid());
        Posyandu::create($validated);

        return redirect()->route('admin.posyandu.index')
            ->with('success', 'Posyandu berhasil ditambahkan.');
    }

    public function edit(Posyandu $posyandu)
    {
        return view('admin.posyandu.edit', compact('posyandu'));
    }

    public function update(Request $request, Posyandu $posyandu)
    {
        $validated = $request->validate([
            'nama'    => 'required|string|max:100',
            'kode'    => 'required|string|max:20|unique:posyandu,kode,' . $posyandu->id,
            'kelurahan'        => 'nullable|string|max:100',
            'kecamatan'        => 'nullable|string|max:100',
            'kota'             => 'nullable|string|max:100',
            'alamat'           => 'nullable|string',
            'is_active'        => 'boolean',
        ]);

        $posyandu->update($validated);

        return redirect()->route('admin.posyandu.index')
            ->with('success', 'Posyandu berhasil diperbarui.');
    }

    public function destroy(Posyandu $posyandu)
    {
        $posyandu->update(['is_active' => false]);
        return redirect()->route('admin.posyandu.index')
            ->with('success', 'Posyandu berhasil dinonaktifkan.');
    }
}