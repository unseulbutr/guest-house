<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingExtension;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingExtensionController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    |
    | Customer membuka form perpanjangan.
    |
    */

    public function create(
        Request $request,
        Booking $booking
    ) {
        abort_unless(
            $booking->customer_id === $request->user()->id,
            403,
            'Anda tidak berhak mengakses booking ini.'
        );

        /*
        |--------------------------------------------------------------------------
        | BOOKING HARUS MASIH AKTIF
        |--------------------------------------------------------------------------
        */

        abort_unless(
            in_array(
                $booking->status,
                [
                    'pending',
                    'confirmed',
                ]
            ),
            400,
            'Booking ini tidak dapat diperpanjang.'
        );

        /*
        |--------------------------------------------------------------------------
        | HARUS SUDAH BAYAR
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $booking->payment_status === 'paid',
            400,
            'Booking harus sudah dibayar sebelum dapat diperpanjang.'
        );

        /*
        |--------------------------------------------------------------------------
        | CEK REQUEST PENDING
        |--------------------------------------------------------------------------
        */

        $pendingExtension = BookingExtension::where(
            'booking_id',
            $booking->id
        )
            ->where(
                'status',
                'pending'
            )
            ->exists();

        return view(
            'bookings.extensions.create',
            compact(
                'booking',
                'pendingExtension'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    |
    | Customer mengajukan perpanjangan.
    |
    */

    public function store(
        Request $request,
        Booking $booking
    ) {
        abort_unless(
            $booking->customer_id === $request->user()->id,
            403,
            'Anda tidak berhak mengubah booking ini.'
        );

        abort_unless(
            in_array(
                $booking->status,
                [
                    'pending',
                    'confirmed',
                ]
            ),
            400,
            'Booking ini tidak dapat diperpanjang.'
        );

        abort_unless(
            $booking->payment_status === 'paid',
            400,
            'Booking harus sudah dibayar sebelum dapat diperpanjang.'
        );

        /*
        |--------------------------------------------------------------------------
        | VALIDASI
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'new_check_out' => [
                'required',
                'date',
                'after:' .
                $booking->check_out->format('Y-m-d'),
            ],

            'customer_note' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ], [
            'new_check_out.required' =>
                'Tanggal checkout baru wajib dipilih.',

            'new_check_out.date' =>
                'Tanggal checkout tidak valid.',

            'new_check_out.after' =>
                'Tanggal checkout baru harus setelah checkout saat ini.',

            'customer_note.max' =>
                'Catatan maksimal 1000 karakter.',
        ]);

        /*
        |--------------------------------------------------------------------------
        | CEK REQUEST PENDING
        |--------------------------------------------------------------------------
        */

        $hasPending = BookingExtension::where(
            'booking_id',
            $booking->id
        )
            ->where(
                'status',
                'pending'
            )
            ->exists();

        if ($hasPending) {

            return back()
                ->withInput()
                ->with(
                    'extension_error',
                    'Masih ada permintaan perpanjangan yang sedang diproses.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | TRANSACTION
        |--------------------------------------------------------------------------
        */

        $extension = DB::transaction(
            function () use (
                $booking,
                $validated
            ) {

                /*
                |--------------------------------------------------------------------------
                | LOCK BOOKING
                |--------------------------------------------------------------------------
                */

                $lockedBooking = Booking::where(
                    'id',
                    $booking->id
                )
                    ->lockForUpdate()
                    ->firstOrFail();


                /*
                |--------------------------------------------------------------------------
                | CEK ULANG STATUS
                |--------------------------------------------------------------------------
                */

                if (
                    !in_array(
                        $lockedBooking->status,
                        [
                            'pending',
                            'confirmed',
                        ]
                    )
                ) {
                    return null;
                }


                /*
                |--------------------------------------------------------------------------
                | TANGGAL LAMA
                |--------------------------------------------------------------------------
                */

                $oldCheckOut =
                    Carbon::parse(
                        $lockedBooking->check_out
                    );


                /*
                |--------------------------------------------------------------------------
                | TANGGAL BARU
                |--------------------------------------------------------------------------
                */

                $newCheckOut =
                    Carbon::parse(
                        $validated['new_check_out']
                    );


                /*
                |--------------------------------------------------------------------------
                | JUMLAH MALAM TAMBAHAN
                |--------------------------------------------------------------------------
                */

                $additionalNights =
                    $oldCheckOut->diffInDays(
                        $newCheckOut
                    );


                if ($additionalNights < 1) {
                    return null;
                }


                /*
                |--------------------------------------------------------------------------
                | CEK BOOKING LAIN
                |--------------------------------------------------------------------------
                |
                | Misalnya:
                |
                | Booking sekarang:
                | 10 - 13
                |
                | Mau diperpanjang:
                | 10 - 16
                |
                | Maka kita mengecek apakah ada booking lain
                | mulai dari tanggal 13 sampai 16.
                |
                */

                $conflict = Booking::where(
                    'property_id',
                    $lockedBooking->property_id
                )
                    ->where(
                        'id',
                        '!=',
                        $lockedBooking->id
                    )
                    ->whereIn(
                        'status',
                        [
                            'pending',
                            'confirmed',
                        ]
                    )
                    ->where(
                        'check_in',
                        '<',
                        $newCheckOut->format('Y-m-d')
                    )
                    ->where(
                        'check_out',
                        '>',
                        $oldCheckOut->format('Y-m-d')
                    )
                    ->exists();


                if ($conflict) {
                    return null;
                }


                /*
                |--------------------------------------------------------------------------
                | PROPERTY
                |--------------------------------------------------------------------------
                */

                $property =
                    $lockedBooking->property;


                /*
                |--------------------------------------------------------------------------
                | TAMBAHAN HARGA
                |--------------------------------------------------------------------------
                */

                $additionalAmount =
                    $property->price_per_night *
                    $additionalNights;


                /*
                |--------------------------------------------------------------------------
                | CREATE REQUEST
                |--------------------------------------------------------------------------
                */

                return BookingExtension::create([

                    'booking_id' =>
                        $lockedBooking->id,

                    'old_check_out' =>
                        $oldCheckOut->format('Y-m-d'),

                    'new_check_out' =>
                        $newCheckOut->format('Y-m-d'),

                    'additional_nights' =>
                        $additionalNights,

                    'additional_amount' =>
                        $additionalAmount,

                    'status' =>
                        'pending',

                    'customer_note' =>
                        $validated['customer_note'] ?? null,
                ]);
            }
        );


        /*
        |--------------------------------------------------------------------------
        | GAGAL / BENTROK
        |--------------------------------------------------------------------------
        */

        if (!$extension) {

            return back()
                ->withInput()
                ->with(
                    'extension_error',
                    'Tanggal perpanjangan tidak tersedia atau booking sudah berubah.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | BERHASIL
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'customer.bookings.show',
                $booking
            )
            ->with(
                'success',
                'Permintaan perpanjangan menginap berhasil dikirim. Menunggu konfirmasi mitra.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | MITRA INDEX
    |--------------------------------------------------------------------------
    |
    | Semua request extension milik property mitra.
    |
    */

    public function indexForMitra(
        Request $request
    ) {
        $extensions = BookingExtension::with([
            'booking.property',
            'booking.customer',
        ])
            ->whereHas(
                'booking.property',
                function ($query) use ($request) {

                    $query->where(
                        'mitra_id',
                        $request->user()->id
                    );
                }
            )
            ->latest()
            ->paginate(10);

        return view(
            'mitra.booking-extensions.index',
            compact('extensions')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(
        Request $request,
        BookingExtension $extension
    ) {
        $booking =
            $extension->booking;

        $this->authorizeExtensionAccess(
            $request,
            $extension
        );

        $extension->load([
            'booking.property',
            'booking.customer',
        ]);

        return view(
            'bookings.extensions.show',
            compact(
                'extension'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | APPROVE
    |--------------------------------------------------------------------------
    */

    public function approve(
        Request $request,
        BookingExtension $extension
    ) {
        $this->authorizeMitra(
            $request,
            $extension
        );

        abort_unless(
            $extension->status === 'pending',
            400,
            'Permintaan perpanjangan ini sudah diproses.'
        );


        $result = DB::transaction(
            function () use (
                $extension
            ) {

                /*
                |--------------------------------------------------------------------------
                | LOCK EXTENSION
                |--------------------------------------------------------------------------
                */

                $lockedExtension =
                    BookingExtension::where(
                        'id',
                        $extension->id
                    )
                        ->lockForUpdate()
                        ->firstOrFail();


                if (
                    $lockedExtension->status !==
                    'pending'
                ) {
                    return false;
                }


                /*
                |--------------------------------------------------------------------------
                | LOCK BOOKING
                |--------------------------------------------------------------------------
                */

                $booking =
                    Booking::where(
                        'id',
                        $lockedExtension->booking_id
                    )
                        ->lockForUpdate()
                        ->firstOrFail();


                /*
                |--------------------------------------------------------------------------
                | BOOKING HARUS AKTIF
                |--------------------------------------------------------------------------
                */

                if (
                    !in_array(
                        $booking->status,
                        [
                            'pending',
                            'confirmed',
                        ]
                    )
                ) {
                    return false;
                }


                /*
                |--------------------------------------------------------------------------
                | CEK BENTROK SEKALI LAGI
                |--------------------------------------------------------------------------
                */

                $conflict =
                    Booking::where(
                        'property_id',
                        $booking->property_id
                    )
                        ->where(
                            'id',
                            '!=',
                            $booking->id
                        )
                        ->whereIn(
                            'status',
                            [
                                'pending',
                                'confirmed',
                            ]
                        )
                        ->where(
                            'check_in',
                            '<',
                            $lockedExtension->new_check_out
                        )
                        ->where(
                            'check_out',
                            '>',
                            $booking->check_out
                        )
                        ->exists();


                if ($conflict) {
                    return false;
                }


                /*
                |--------------------------------------------------------------------------
                | UPDATE BOOKING
                |--------------------------------------------------------------------------
                */

                $booking->update([

                    'check_out' =>
                        $lockedExtension
                            ->new_check_out,

                    'subtotal' =>
                        $booking->subtotal +
                        $lockedExtension
                            ->additional_amount,

                    'commission_amount' =>
                        (
                            $booking->subtotal +
                            $lockedExtension
                                ->additional_amount
                        ) *
                        (
                            $booking
                                ->commission_percentage
                            / 100
                        ),

                    'mitra_payout_amount' =>
                        (
                            $booking->subtotal +
                            $lockedExtension
                                ->additional_amount
                        )
                        -
                        (
                            (
                                $booking->subtotal +
                                $lockedExtension
                                    ->additional_amount
                            )
                            *
                            (
                                $booking
                                    ->commission_percentage
                                / 100
                            )
                        ),

                    'total_price' =>
                        $booking->total_price +
                        $lockedExtension
                            ->additional_amount,
                ]);


                /*
                |--------------------------------------------------------------------------
                | UPDATE EXTENSION
                |--------------------------------------------------------------------------
                */

                $lockedExtension->update([

                    'status' =>
                        'approved',

                    'approved_at' =>
                        now(),
                ]);


                return true;
            }
        );


        if (!$result) {

            return back()->with(
                'error',
                'Perpanjangan tidak dapat disetujui karena tanggal sudah tidak tersedia atau booking sudah berubah.'
            );
        }


        return back()->with(
            'success',
            'Perpanjangan menginap berhasil disetujui.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | REJECT
    |--------------------------------------------------------------------------
    */

    public function reject(
        Request $request,
        BookingExtension $extension
    ) {
        $this->authorizeMitra(
            $request,
            $extension
        );

        abort_unless(
            $extension->status === 'pending',
            400,
            'Permintaan perpanjangan ini sudah diproses.'
        );


        $validated =
            $request->validate([
                'rejection_reason' =>
                    'required|string|max:1000',
            ], [
                'rejection_reason.required' =>
                    'Alasan penolakan wajib diisi.',
            ]);


        $extension->update([

            'status' =>
                'rejected',

            'rejection_reason' =>
                $validated['rejection_reason'],
        ]);


        return back()->with(
            'success',
            'Permintaan perpanjangan ditolak.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CUSTOMER CANCEL REQUEST
    |--------------------------------------------------------------------------
    */

    public function cancel(
        Request $request,
        BookingExtension $extension
    ) {
        abort_unless(
            $extension->booking->customer_id ===
            $request->user()->id,
            403,
            'Anda tidak berhak membatalkan permintaan ini.'
        );


        abort_unless(
            $extension->status === 'pending',
            400,
            'Permintaan ini sudah diproses.'
        );


        $extension->update([
            'status' =>
                'cancelled',
        ]);


        return back()->with(
            'success',
            'Permintaan perpanjangan dibatalkan.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | AUTHORIZE EXTENSION
    |--------------------------------------------------------------------------
    */

    protected function authorizeExtensionAccess(
        Request $request,
        BookingExtension $extension
    ): void {

        $user =
            $request->user();

        $booking =
            $extension->booking;


        $allowed =
            $user->hasRole('admin')
            ||
            $user->hasRole('super_admin')
            ||
            (
                $user->hasRole('customer')
                &&
                $booking->customer_id ===
                $user->id
            )
            ||
            (
                $user->hasRole('mitra')
                &&
                $booking->property->mitra_id ===
                $user->id
            );


        abort_unless(
            $allowed,
            403,
            'Anda tidak berhak mengakses permintaan ini.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | AUTHORIZE MITRA
    |--------------------------------------------------------------------------
    */

    protected function authorizeMitra(
        Request $request,
        BookingExtension $extension
    ): void {

        $booking =
            $extension->booking;


        abort_unless(
            $request->user()->hasRole('mitra')
            &&
            $booking->property->mitra_id ===
            $request->user()->id,
            403,
            'Anda tidak berhak memproses permintaan ini.'
        );
    }
}