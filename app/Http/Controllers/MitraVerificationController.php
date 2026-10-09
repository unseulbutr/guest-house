<?php

namespace App\Http\Controllers;

use App\Models\MitraVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MitraVerificationController extends Controller
{
    /**
     * Menampilkan daftar pengajuan Mitra.
     */
    public function index(Request $request)
    {
        $query = MitraVerification::query()
            ->with([
                'user',
                'verifier',
            ]);

        /*
        |--------------------------------------------------------------------------
        | FILTER STATUS
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->status
            );
        }


        /*
        |--------------------------------------------------------------------------
        | PENCARIAN
        |--------------------------------------------------------------------------
        |
        | Bisa mencari berdasarkan:
        | - Nama
        | - Email
        | - Nomor identitas
        |
        */

        if ($request->filled('search')) {

            $search = trim(
                $request->search
            );

            $query->where(function ($q) use ($search) {

                $q->where(
                    'identity_number',
                    'like',
                    "%{$search}%"
                )

                ->orWhereHas(
                    'user',
                    function ($userQuery) use ($search) {

                        $userQuery
                            ->where(
                                'name',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'email',
                                'like',
                                "%{$search}%"
                            );
                    }
                );

            });
        }


        /*
        |--------------------------------------------------------------------------
        | DATA PENGAJUAN
        |--------------------------------------------------------------------------
        */

        $verifications = $query
            ->latest('submitted_at')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | STATISTIK
        |--------------------------------------------------------------------------
        */

        $statistics = [

            'all' => MitraVerification::count(),

            'pending' => MitraVerification::where(
                'status',
                'pending'
            )->count(),

            'approved' => MitraVerification::where(
                'status',
                'approved'
            )->count(),

            'rejected' => MitraVerification::where(
                'status',
                'rejected'
            )->count(),

        ];


        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.mitra-verifications.index',
            compact(
                'verifications',
                'statistics'
            )
        );
    }


    /**
     * Menampilkan detail pengajuan Mitra.
     */
    public function show(
        MitraVerification $verification
    ) {
        $verification->load([
            'user',
            'verifier',
        ]);

        return view(
            'admin.mitra-verifications.show',
            compact(
                'verification'
            )
        );
    }


    /**
     * Menyetujui pengajuan Mitra.
     *
     * Setelah disetujui:
     *
     * Customer
     *     ↓
     * Mitra
     */
    public function approve(
        Request $request,
        MitraVerification $verification
    ) {
        /*
        |--------------------------------------------------------------------------
        | CEK STATUS
        |--------------------------------------------------------------------------
        */

        if ($verification->status === 'approved') {

            return back()->with(
                'success',
                'Pengajuan ini sudah disetujui sebelumnya.'
            );
        }


        if ($verification->status !== 'pending') {

            return back()->with(
                'error',
                'Hanya pengajuan yang masih menunggu verifikasi yang dapat disetujui.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | AMBIL USER
        |--------------------------------------------------------------------------
        */

        $verification->load('user');

        $user = $verification->user;


        if (! $user) {

            return back()->with(
                'error',
                'Data pengguna dari pengajuan ini tidak ditemukan.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | UBAH ROLE MENJADI MITRA
        |--------------------------------------------------------------------------
        |
        | Setelah Admin / Super Admin menyetujui:
        |
        | customer → mitra
        |
        | syncRoles() memastikan role lama diganti
        | dengan role mitra.
        |
        */

        $user->syncRoles([
            'mitra',
        ]);


        /*
        |--------------------------------------------------------------------------
        | UPDATE VERIFIKASI
        |--------------------------------------------------------------------------
        */

        $verification->update([

            'status' => 'approved',

            'rejection_reason' => null,

            'verified_at' => now(),

            'verified_by' => $request->user()->id,

        ]);


        /*
        |--------------------------------------------------------------------------
        | KEMBALI KE DAFTAR
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'admin.mitra-verifications.index'
            )
            ->with(
                'success',
                "Pengajuan Mitra \"{$user->name}\" berhasil disetujui. Akun sekarang menjadi Mitra."
            );
    }


    /**
     * Menolak pengajuan Mitra.
     */
    public function reject(
        Request $request,
        MitraVerification $verification
    ) {

        /*
        |--------------------------------------------------------------------------
        | VALIDASI ALASAN PENOLAKAN
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'rejection_reason' => [
                'required',
                'string',
                'min:5',
                'max:2000',
            ],

        ], [

            'rejection_reason.required' =>
                'Alasan penolakan wajib diisi.',

            'rejection_reason.min' =>
                'Alasan penolakan minimal 5 karakter.',

            'rejection_reason.max' =>
                'Alasan penolakan maksimal 2000 karakter.',

        ]);


        /*
        |--------------------------------------------------------------------------
        | CEK STATUS
        |--------------------------------------------------------------------------
        */

        if ($verification->status === 'approved') {

            return back()->with(
                'error',
                'Pengajuan yang sudah disetujui tidak dapat ditolak.'
            );
        }


        if ($verification->status !== 'pending') {

            return back()->with(
                'error',
                'Hanya pengajuan yang masih menunggu verifikasi yang dapat ditolak.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | UPDATE STATUS
        |--------------------------------------------------------------------------
        */

        $verification->update([

            'status' => 'rejected',

            'rejection_reason' =>
                $validated['rejection_reason'],

            'verified_at' => null,

            'verified_by' =>
                $request->user()->id,

        ]);


        /*
        |--------------------------------------------------------------------------
        | KEMBALI KE DAFTAR
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'admin.mitra-verifications.index'
            )
            ->with(
                'success',
                'Pengajuan Mitra berhasil ditolak.'
            );
    }


    /**
     * Menampilkan foto identitas / selfie secara private.
     *
     * File tidak disimpan di public/storage.
     *
     * Type:
     * - identity
     * - selfie
     */
    public function document(
        MitraVerification $verification,
        string $type
    ) {

        /*
        |--------------------------------------------------------------------------
        | TENTUKAN FILE
        |--------------------------------------------------------------------------
        */

        $field = match ($type) {

            'identity' => 'identity_photo',

            'selfie' => 'selfie_photo',

            default => null,

        };


        /*
        |--------------------------------------------------------------------------
        | TYPE TIDAK VALID
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $field !== null,
            404
        );


        /*
        |--------------------------------------------------------------------------
        | PATH FILE
        |--------------------------------------------------------------------------
        */

        $path = $verification->{$field};


        abort_unless(
            $path,
            404
        );


        /*
        |--------------------------------------------------------------------------
        | STORAGE PRIVATE
        |--------------------------------------------------------------------------
        */

        $disk = Storage::disk(
            'local'
        );


        /*
        |--------------------------------------------------------------------------
        | FILE TIDAK DITEMUKAN
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $disk->exists($path),
            404
        );


        /*
        |--------------------------------------------------------------------------
        | TAMPILKAN FILE
        |--------------------------------------------------------------------------
        |
        | File dikirim langsung dari storage private.
        | Tidak perlu memindahkan KTP/selfie ke public.
        |
        */

        return response()->file(
            $disk->path($path),
            [
                'Cache-Control' =>
                    'private, no-store, no-cache, must-revalidate',

                'Pragma' =>
                    'no-cache',

            ]
        );
    }
}