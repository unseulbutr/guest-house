<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * List semua user, bisa difilter per role & dicari nama/email.
     * Middleware route: role:super_admin SAJA (lihat routes/web.php) — Admin
     * biasa tidak boleh akses ini, sesuai spec poin 2a.
     */
    public function index(Request $request)
    {
        $query = User::query()->with('roles');

        if ($request->filled('role')) {
            $query->whereHas('roles', fn ($q) => $q->where('name', $request->role));
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(15)->withQueryString();
        $roles = Role::pluck('name');

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $roles = Role::pluck('name');
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:30',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => $validated['password'], // otomatis di-hash lewat cast di model
            'is_active' => true,
        ]);

        $user->assignRole($validated['role']);

        return redirect()->route('admin.users.index')
            ->with('success', "User \"{$user->name}\" berhasil ditambahkan sebagai {$validated['role']}.");
    }

    public function edit(User $user)
    {
        $roles = Role::pluck('name');
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => 'nullable|string|max:30',
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|exists:roles,name',
        ]);

        // Jaga-jaga: super admin tidak bisa menurunkan role akun miliknya sendiri —
        // supaya tidak ada kejadian "tidak ada super admin tersisa" secara tidak sengaja.
        if ($user->id === auth()->id() && $validated['role'] !== 'super_admin') {
            return back()->withErrors(['role' => 'Anda tidak bisa mengubah role akun Anda sendiri.'])->withInput();
        }

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
        ]);

        if (! empty($validated['password'])) {
            $user->update(['password' => $validated['password']]);
        }

        $user->syncRoles([$validated['role']]);

        return redirect()->route('admin.users.index')
            ->with('success', "Data \"{$user->name}\" berhasil diperbarui.");
    }

    /**
     * Nonaktifkan/aktifkan akun (bukan hard-delete) — supaya data histori
     * booking/properti yang masih terhubung ke user ini tetap aman.
     */
    public function toggleActive(Request $request, User $user)
    {
        abort_if($user->id === auth()->id(), 403, 'Anda tidak bisa menonaktifkan akun Anda sendiri.');

        $user->update(['is_active' => ! $user->is_active]);

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Akun \"{$user->name}\" berhasil {$status}.");
    }
}