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
     *
     * Middleware route:
     * role:super_admin
     *
     * Admin biasa tidak boleh mengakses halaman ini.
     */
    public function index(Request $request)
    {
        $query = User::query()->with('roles');

        // Filter berdasarkan role
        if ($request->filled('role')) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        // Pencarian nama atau email
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $roles = Role::pluck('name');

        return view(
            'admin.users.index',
            compact('users', 'roles')
        );
    }

    /**
     * Form tambah user.
     */
    public function create()
    {
        $roles = Role::pluck('name');

        return view(
            'admin.users.create',
            compact('roles')
        );
    }

    /**
     * Simpan user baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'unique:users,email',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:30',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],

            'role' => [
                'required',
                'exists:roles,name',
            ],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,

            // Password otomatis di-hash
            // karena model User menggunakan cast password => hashed
            'password' => $validated['password'],

            'is_active' => true,
        ]);

        $user->assignRole($validated['role']);

        return redirect()
            ->route('admin.users.index')
            ->with(
                'success',
                "User \"{$user->name}\" berhasil ditambahkan sebagai {$validated['role']}."
            );
    }

    /**
     * Form edit user.
     */
    public function edit(User $user)
    {
        $roles = Role::pluck('name');

        return view(
            'admin.users.edit',
            compact('user', 'roles')
        );
    }

    /**
     * Update data user.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')
                    ->ignore($user->id),
            ],

            'phone' => [
                'nullable',
                'string',
                'max:30',
            ],

            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],

            'role' => [
                'required',
                'exists:roles,name',
            ],
        ]);

        /**
         * Super Admin tidak boleh menurunkan role
         * akun miliknya sendiri.
         */
        if (
            $user->id === auth()->id() &&
            $validated['role'] !== 'super_admin'
        ) {
            return back()
                ->withErrors([
                    'role' => 'Anda tidak bisa mengubah role akun Anda sendiri.',
                ])
                ->withInput();
        }

        // Update data utama
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
        ]);

        // Update password hanya jika diisi
        if (! empty($validated['password'])) {
            $user->update([
                'password' => $validated['password'],
            ]);
        }

        // Sinkronisasi role
        $user->syncRoles([
            $validated['role'],
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with(
                'success',
                "Data \"{$user->name}\" berhasil diperbarui."
            );
    }

    /**
     * Aktifkan / nonaktifkan akun.
     *
     * Menggunakan soft-disable melalui is_active,
     * bukan menghapus user secara permanen.
     *
     * Data booking, properti, dan histori user tetap aman.
     */
    public function toggleActive(Request $request, User $user)
    {
        // Tidak boleh menonaktifkan akun sendiri
        abort_if(
            $user->id === auth()->id(),
            403,
            'Anda tidak bisa menonaktifkan akun Anda sendiri.'
        );

        // Toggle status akun
        $user->update([
            'is_active' => ! $user->is_active,
        ]);

        $status = $user->is_active
            ? 'diaktifkan'
            : 'dinonaktifkan';

        return back()->with(
            'success',
            "Akun \"{$user->name}\" berhasil {$status}."
        );
    }
}