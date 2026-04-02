<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Posyandu;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('posyandu')->where('role', 'petugas');

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
        }

        $users = $query->orderBy('name')->paginate(10)->withQueryString();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $posyandu = Posyandu::where('is_active', true)->get();
        return view('admin.users.create', compact('posyandu'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:100',
            'email'        => 'required|email|unique:users',
            'password'     => 'required|min:8|confirmed',
            'no_hp'        => 'nullable|string|max:20',
            'posyandu_id' => 'nullable|array',
            'posyandu_id.*' => 'exists:posyandu,id',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => 'petugas',
            'no_hp'    => $validated['no_hp'] ?? null,
            'is_active' => true,
        ]);

        if (!empty($validated['posyandu_ids'])) {
            $user->posyandu()->sync($validated['posyandu_ids']);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'Petugas berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $posyandu = Posyandu::where('is_active', true)->get();
        $selectedPosyandu = $user->posyandu->pluck('id')->toArray();
        return view('admin.users.edit', compact('user', 'posyandu', 'selectedPosyandu'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:100',
            'email'        => 'required|email|unique:users,email,' . $user->id,
            'password'     => 'nullable|min:8|confirmed',
            'no_hp'        => 'nullable|string|max:20',
            'is_active'    => 'boolean',
            'posyandu_id' => 'nullable|array',
            'posyandu_id.*' => 'exists:posyandu,id',
        ]);

        $updateData = [
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'no_hp'     => $validated['no_hp'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);
        $user->posyandu()->sync($validated['posyandu_ids'] ?? []);

        return redirect()->route('admin.users.index')
            ->with('success', 'Data petugas berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->update(['is_active' => false]);
        return redirect()->route('admin.users.index')
            ->with('success', 'Akun petugas berhasil dinonaktifkan.');
    }
}