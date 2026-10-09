<?php

namespace App\Http\Controllers;

use App\Models\MitraVerification;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class MitraRegistrationController extends Controller
{
    /**
     * Menampilkan halaman informasi / alur pendaftaran Mitra.
     *
     * Halaman ini menjadi halaman pertama ketika:
     * - Guest memilih "Mitra" pada halaman registrasi
     * - Customer memilih menu untuk menjadi Mitra
     *
     * Dari halaman ini user baru melanjutkan ke form pendaftaran.
     */
    public function landing(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        /*
        |--------------------------------------------------------------------------
        | Jika sudah menjadi Mitra
        |--------------------------------------------------------------------------
        */

        if ($user && $user->hasRole('mitra')) {
            return redirect()
                ->route('dashboard')
                ->with(
                    'success',
                    'Akun Anda sudah terdaftar sebagai Mitra.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Jika Customer sudah memiliki pengajuan yang masih pending
        |--------------------------------------------------------------------------
        */

        if ($user) {
            $verification = $user->mitraVerification;

            if (
                $verification &&
                $verification->status === 'pending'
            ) {
                return redirect()
                    ->route('mitra.apply.status');
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Tampilkan halaman alur Mitra
        |--------------------------------------------------------------------------
        */

        return view('mitra.landing');
    }

    /**
     * Menampilkan halaman form pendaftaran / pengajuan menjadi Mitra.
     *
     * Bisa diakses oleh:
     * - Guest / orang yang belum punya akun
     * - Customer yang sudah punya akun
     */
    public function create(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        /*
        |--------------------------------------------------------------------------
        | Jika sudah menjadi Mitra
        |--------------------------------------------------------------------------
        */

        if ($user && $user->hasRole('mitra')) {
            return redirect()
                ->route('dashboard')
                ->with(
                    'success',
                    'Akun Anda sudah terdaftar sebagai Mitra.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Ambil data pengajuan jika user sudah login
        |--------------------------------------------------------------------------
        */

        $verification = $user
            ? $user->mitraVerification
            : null;

        /*
        |--------------------------------------------------------------------------
        | Jika masih menunggu verifikasi
        |--------------------------------------------------------------------------
        */

        if (
            $verification &&
            $verification->status === 'pending'
        ) {
            return view(
                'mitra.apply-status',
                compact('verification')
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Tampilkan form
        |--------------------------------------------------------------------------
        |
        | Guest:
        | - Membuat akun baru
        |
        | Customer:
        | - Menggunakan akun yang sudah ada
        |
        */

        return view(
            'mitra.register',
            compact('verification', 'user')
        );
    }

    /**
     * Menyimpan pendaftaran / pengajuan menjadi Mitra.
     *
     * Guest:
     * - Membuat akun baru sebagai Customer
     * - Membuat pengajuan verifikasi Mitra
     * - Login otomatis
     *
     * Customer:
     * - Menggunakan akun yang sudah ada
     * - Membuat pengajuan verifikasi Mitra
     *
     * Role Mitra TIDAK diberikan di sini.
     *
     * Role Mitra hanya diberikan setelah Admin / Super Admin
     * menyetujui pengajuan melalui MitraVerificationController.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        /*
        |--------------------------------------------------------------------------
        | Jangan izinkan Mitra mengajukan lagi
        |--------------------------------------------------------------------------
        */

        if ($user && $user->hasRole('mitra')) {
            return redirect()
                ->route('dashboard')
                ->with(
                    'success',
                    'Akun Anda sudah terdaftar sebagai Mitra.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Validasi dasar identitas
        |--------------------------------------------------------------------------
        */

        $rules = [
            'identity_type' => [
                'required',
                'in:ktp,sim,paspor',
            ],

            'identity_number' => [
                'required',
                'string',
                'max:100',
            ],

            'identity_photo' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'selfie_photo' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'terms' => [
                'accepted',
            ],
        ];

        /*
        |--------------------------------------------------------------------------
        | Jika Guest
        |--------------------------------------------------------------------------
        |
        | Guest harus membuat akun terlebih dahulu.
        |
        */

        if (! $user) {
            $rules = array_merge(
                [
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
                        'max:30',
                    ],

                    'password' => [
                        'required',
                        'confirmed',
                        Rules\Password::defaults(),
                    ],
                ],
                $rules
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Pesan validasi
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate(
            $rules,
            [
                'name.required' =>
                    'Nama lengkap wajib diisi.',

                'name.max' =>
                    'Nama lengkap terlalu panjang.',

                'email.required' =>
                    'Email wajib diisi.',

                'email.email' =>
                    'Format email tidak valid.',

                'email.unique' =>
                    'Email tersebut sudah terdaftar. Silakan login terlebih dahulu.',

                'phone.max' =>
                    'Nomor telepon terlalu panjang.',

                'password.required' =>
                    'Password wajib diisi.',

                'password.confirmed' =>
                    'Konfirmasi password tidak sama.',

                'identity_type.required' =>
                    'Jenis identitas wajib dipilih.',

                'identity_type.in' =>
                    'Jenis identitas tidak valid.',

                'identity_number.required' =>
                    'Nomor identitas wajib diisi.',

                'identity_number.max' =>
                    'Nomor identitas terlalu panjang.',

                'identity_photo.required' =>
                    'Foto identitas wajib diunggah.',

                'identity_photo.image' =>
                    'File identitas harus berupa gambar.',

                'identity_photo.mimes' =>
                    'Foto identitas harus JPG, JPEG, PNG, atau WEBP.',

                'identity_photo.max' =>
                    'Ukuran foto identitas maksimal 5 MB.',

                'selfie_photo.required' =>
                    'Foto selfie wajib diambil.',

                'selfie_photo.image' =>
                    'File selfie harus berupa gambar.',

                'selfie_photo.mimes' =>
                    'Foto selfie harus JPG, JPEG, PNG, atau WEBP.',

                'selfie_photo.max' =>
                    'Ukuran foto selfie maksimal 5 MB.',

                'terms.accepted' =>
                    'Anda harus menyetujui syarat pengajuan menjadi Mitra.',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Ambil pengajuan yang sudah ada
        |--------------------------------------------------------------------------
        */

        $verification = $user
            ? $user->mitraVerification
            : null;

        /*
        |--------------------------------------------------------------------------
        | Pengajuan pending tidak boleh dikirim ulang
        |--------------------------------------------------------------------------
        */

        if (
            $verification &&
            $verification->status === 'pending'
        ) {
            return redirect()
                ->route('mitra.apply.status')
                ->with(
                    'error',
                    'Pengajuan Anda masih dalam proses verifikasi.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Buat akun baru jika Guest
        |--------------------------------------------------------------------------
        */

        if (! $user) {
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
            | Guest sementara menjadi Customer
            |--------------------------------------------------------------------------
            |
            | Jangan langsung memberikan role Mitra.
            | Role Mitra diberikan setelah Admin / Super Admin menyetujui.
            |
            */

            $user->assignRole('customer');

            /*
            |--------------------------------------------------------------------------
            | Event Registered Laravel
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
            | Refresh user
            |--------------------------------------------------------------------------
            */

            $user->refresh();

            $verification = null;
        }

        /*
        |--------------------------------------------------------------------------
        | Hapus dokumen lama jika pengajuan sebelumnya ditolak
        |--------------------------------------------------------------------------
        */

        if ($verification) {
            if (
                ! empty($verification->identity_photo) &&
                Storage::disk('local')->exists(
                    $verification->identity_photo
                )
            ) {
                Storage::disk('local')->delete(
                    $verification->identity_photo
                );
            }

            if (
                ! empty($verification->selfie_photo) &&
                Storage::disk('local')->exists(
                    $verification->selfie_photo
                )
            ) {
                Storage::disk('local')->delete(
                    $verification->selfie_photo
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Simpan foto identitas secara PRIVATE
        |--------------------------------------------------------------------------
        */

        $identityPhoto = $request
            ->file('identity_photo')
            ->store(
                'mitra-verifications/identity',
                'local'
            );

        /*
        |--------------------------------------------------------------------------
        | Simpan selfie secara PRIVATE
        |--------------------------------------------------------------------------
        */

        $selfiePhoto = $request
            ->file('selfie_photo')
            ->store(
                'mitra-verifications/selfie',
                'local'
            );

        /*
        |--------------------------------------------------------------------------
        | Simpan / perbarui pengajuan
        |--------------------------------------------------------------------------
        */

        $verificationData = [
            'identity_type' =>
                $validated['identity_type'],

            'identity_number' =>
                $validated['identity_number'],

            'identity_photo' =>
                $identityPhoto,

            'selfie_photo' =>
                $selfiePhoto,

            'status' =>
                'pending',

            'rejection_reason' =>
                null,

            'terms_accepted_at' =>
                now(),

            'submitted_at' =>
                now(),

            'verified_at' =>
                null,

            'verified_by' =>
                null,
        ];

        /*
        |--------------------------------------------------------------------------
        | Update pengajuan lama
        |--------------------------------------------------------------------------
        */

        if ($verification) {
            $verification->update(
                $verificationData
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Buat pengajuan baru
        |--------------------------------------------------------------------------
        */

        else {
            $verification = MitraVerification::create(
                array_merge(
                    [
                        'user_id' => $user->id,
                    ],
                    $verificationData
                )
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Pastikan login menggunakan user yang benar
        |--------------------------------------------------------------------------
        */

        if (! Auth::check()) {
            Auth::login($user);
        }

        /*
        |--------------------------------------------------------------------------
        | Redirect ke halaman status
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('mitra.apply.status')
            ->with(
                'success',
                'Pengajuan menjadi Mitra berhasil dikirim. Silakan tunggu proses verifikasi Admin atau Super Admin.'
            );
    }

    /**
     * Menampilkan status pengajuan Mitra.
     */
    public function status(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        /*
        |--------------------------------------------------------------------------
        | Halaman status hanya untuk user yang sudah login
        |--------------------------------------------------------------------------
        */

        if (! $user) {
            return redirect()
                ->route('mitra.register');
        }

        /*
        |--------------------------------------------------------------------------
        | Jika sudah menjadi Mitra
        |--------------------------------------------------------------------------
        */

        if ($user->hasRole('mitra')) {
            return redirect()
                ->route('dashboard')
                ->with(
                    'success',
                    'Selamat! Pengajuan Mitra Anda telah disetujui.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Cari pengajuan
        |--------------------------------------------------------------------------
        */

        $verification = $user->mitraVerification;

        /*
        |--------------------------------------------------------------------------
        | Belum pernah mengajukan
        |--------------------------------------------------------------------------
        */

        if (! $verification) {
            return redirect()
                ->route(
                    $user->hasRole('customer')
                        ? 'mitra.apply'
                        : 'mitra.register'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Jika sudah disetujui tetapi role belum berubah
        |--------------------------------------------------------------------------
        |
        | Biasanya tidak terjadi karena proses approval akan langsung
        | menjalankan syncRoles(['mitra']).
        |
        */

        if (
            $verification->status === 'approved' &&
            ! $user->hasRole('mitra')
        ) {
            return view(
                'mitra.apply-status',
                compact('verification')
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Tampilkan status
        |--------------------------------------------------------------------------
        */

        return view(
            'mitra.apply-status',
            compact('verification')
        );
    }
}