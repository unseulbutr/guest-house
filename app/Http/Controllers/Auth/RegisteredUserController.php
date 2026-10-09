<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Menampilkan halaman registrasi biasa.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Menyimpan registrasi akun biasa.
     *
     * Registrasi biasa selalu membuat akun sebagai Customer.
     *
     * Jika ingin menjadi Mitra, pengguna harus menggunakan
     * halaman "Daftar sebagai Mitra" agar melalui proses
     * verifikasi identitas.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:' . User::class . ',email',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:20',
            ],

            'password' => [
                'required',
                'confirmed',
                Rules\Password::defaults(),
            ],
        ], [
            'name.required' =>
                'Nama wajib diisi.',

            'name.max' =>
                'Nama terlalu panjang.',

            'email.required' =>
                'Email wajib diisi.',

            'email.email' =>
                'Format email tidak valid.',

            'email.unique' =>
                'Email tersebut sudah terdaftar.',

            'phone.max' =>
                'Nomor telepon terlalu panjang.',

            'password.required' =>
                'Password wajib diisi.',

            'password.confirmed' =>
                'Konfirmasi password tidak sama.',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Buat akun
        |--------------------------------------------------------------------------
        */

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make(
                $validated['password']
            ),
            'is_active' => true,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Registrasi biasa selalu menjadi Customer
        |--------------------------------------------------------------------------
        |
        | Mitra tidak boleh dipilih langsung dari registrasi biasa.
        | Untuk menjadi Mitra harus melalui proses verifikasi.
        |
        */

        $user->assignRole('customer');

        /*
        |--------------------------------------------------------------------------
        | Event Registered
        |--------------------------------------------------------------------------
        */

        event(new Registered($user));

        /*
        |--------------------------------------------------------------------------
        | Login otomatis
        |--------------------------------------------------------------------------
        */

        Auth::login($user);

        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('home')
            ->with(
                'success',
                'Registrasi berhasil. Selamat datang!'
            );
    }
}