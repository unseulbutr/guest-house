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
    | Menampilkan halaman perpanjangan booking.
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

        abort_unless(
            in_array($booking->status, [
                'pending',
                'confirmed',
            ]),
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
        | CEK EXTENSION AKTIF
        |--------------------------------------------------------------------------
        |
        | Extension dianggap aktif jika:
        |
        | 1. pending
        | 2. approved + belum dibayar
        |
        */

        $pendingExtension = BookingExtension::where(
            'booking_id',
            $booking->id
        )
            ->where('status', 'pending')
            ->latest('created_at')
            ->first();

        $approvedExtension = BookingExtension::where(
            'booking_id',
            $booking->id
        )
            ->where('status', 'approved')
            ->where('payment_status', 'pending')
            ->latest('created_at')
            ->first();

        if ($pendingExtension || $approvedExtension) {
            return redirect()
                ->route(
                    'customer.bookings.show',
                    $booking
                )
                ->with(
                    'error',
                    'Masih ada permintaan perpanjangan yang harus diselesaikan terlebih dahulu.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | CARI BOOKING LAIN
        |--------------------------------------------------------------------------
        */

        $conflictingBookings = Booking::where(
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
                '>=',
                $booking->check_out
            )
            ->orderBy('check_in')
            ->get([
                'id',
                'check_in',
                'check_out',
            ]);

        /*
        |--------------------------------------------------------------------------
        | BUAT DAFTAR TANGGAL TERBLOKIR
        |--------------------------------------------------------------------------
        */

        $blockedDates = [];

        foreach ($conflictingBookings as $conflict) {

            $start = Carbon::parse(
                $conflict->check_in
            );

            $end = Carbon::parse(
                $conflict->check_out
            );

            /*
            | Booking 20 - 23 berarti malam:
            |
            | 20
            | 21
            | 22
            |
            | Tanggal 23 adalah checkout.
            */

            while ($start->lt($end)) {

                $blockedDates[] =
                    $start->format('Y-m-d');

                $start->addDay();
            }
        }

        $blockedDates = array_values(
            array_unique($blockedDates)
        );

        /*
        |--------------------------------------------------------------------------
        | KALENDER
        |--------------------------------------------------------------------------
        */

        $calendarStart = Carbon::parse(
            $booking->check_out
        )->addDay();

        $calendarEnd = Carbon::parse(
            $booking->check_out
        )->addDays(90);

        /*
        |--------------------------------------------------------------------------
        | AVAILABLE DATES
        |--------------------------------------------------------------------------
        */

        $availableDates = [];

        $current = $calendarStart->copy();

        while ($current->lte($calendarEnd)) {

            $date = $current->format('Y-m-d');

            /*
            | Kandidat checkout hanya boleh dipilih
            | jika seluruh malam dari checkout lama
            | sampai checkout baru tersedia.
            */

            $candidateCheckOut = $current->copy();

            $hasConflict = Booking::where(
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
                    $candidateCheckOut->format('Y-m-d')
                )
                ->where(
                    'check_out',
                    '>',
                    Carbon::parse(
                        $booking->check_out
                    )->format('Y-m-d')
                )
                ->exists();

            if (!$hasConflict) {
                $availableDates[] = $date;
            }

            $current->addDay();
        }

        return view(
            'bookings.extensions.create',
            compact(
                'booking',
                'pendingExtension',
                'approvedExtension',
                'blockedDates',
                'availableDates',
                'calendarStart',
                'calendarEnd'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    |
    | Customer membuat permintaan extension.
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
            in_array($booking->status, [
                'pending',
                'confirmed',
            ]),
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
                'after:' . $booking->check_out->format('Y-m-d'),
            ],

            'customer_note' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'agree_terms' => [
                'required',
                'accepted',
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

            'agree_terms.required' =>
                'Anda harus menyetujui syarat dan ketentuan perpanjangan.',

            'agree_terms.accepted' =>
                'Anda harus membaca dan menyetujui syarat dan ketentuan perpanjangan.',
        ]);

        /*
        |--------------------------------------------------------------------------
        | CEK EXTENSION AKTIF
        |--------------------------------------------------------------------------
        */

        $activeExtension = BookingExtension::where(
            'booking_id',
            $booking->id
        )
            ->where(function ($query) {

                $query
                    ->where('status', 'pending')

                    ->orWhere(function ($query) {

                        $query
                            ->where('status', 'approved')
                            ->where('payment_status', 'pending');

                    });

            })
            ->exists();

        if ($activeExtension) {

            return back()
                ->withInput()
                ->with(
                    'extension_error',
                    'Masih ada permintaan perpanjangan yang harus diselesaikan terlebih dahulu.'
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
                | STATUS
                |--------------------------------------------------------------------------
                */

                if (!in_array(
                    $lockedBooking->status,
                    [
                        'pending',
                        'confirmed',
                    ]
                )) {
                    return null;
                }

                /*
                |--------------------------------------------------------------------------
                | PAYMENT
                |--------------------------------------------------------------------------
                */

                if (
                    $lockedBooking->payment_status !== 'paid'
                ) {
                    return null;
                }

                /*
                |--------------------------------------------------------------------------
                | CEK EXTENSION AKTIF LAGI
                |--------------------------------------------------------------------------
                */

                $activeExtension = BookingExtension::where(
                    'booking_id',
                    $lockedBooking->id
                )
                    ->where(function ($query) {

                        $query
                            ->where('status', 'pending')

                            ->orWhere(function ($query) {

                                $query
                                    ->where('status', 'approved')
                                    ->where('payment_status', 'pending');

                            });

                    })
                    ->exists();

                if ($activeExtension) {
                    return null;
                }

                /*
                |--------------------------------------------------------------------------
                | TANGGAL
                |--------------------------------------------------------------------------
                */

                $oldCheckOut = Carbon::parse(
                    $lockedBooking->check_out
                );

                $newCheckOut = Carbon::parse(
                    $validated['new_check_out']
                );

                /*
                |--------------------------------------------------------------------------
                | CEK CHECKOUT
                |--------------------------------------------------------------------------
                */

                if (!$newCheckOut->greaterThan($oldCheckOut)) {
                    return null;
                }

                /*
                |--------------------------------------------------------------------------
                | HITUNG MALAM
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
                | CEK BENTROK
                |--------------------------------------------------------------------------
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

                $property = $lockedBooking->property;

                if (!$property) {
                    return null;
                }

                /*
                |--------------------------------------------------------------------------
                | HARGA
                |--------------------------------------------------------------------------
                */

                $pricePerNight =
                    (float) $property->price_per_night;

                $additionalAmount =
                    $pricePerNight *
                    $additionalNights;

                /*
                |--------------------------------------------------------------------------
                | CREATE EXTENSION
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

                    'payment_method' =>
                        'qris',

                    'payment_status' =>
                        'pending',

                    'qris_transaction_id' =>
                        null,

                    'payment_deadline' =>
                        null,

                    'paid_at' =>
                        null,

                    'customer_note' =>
                        $validated['customer_note'] ?? null,
                ]);
            }
        );

        /*
        |--------------------------------------------------------------------------
        | GAGAL
        |--------------------------------------------------------------------------
        */

        if (!$extension) {

            return back()
                ->withInput()
                ->with(
                    'extension_error',
                    'Tanggal tersebut sudah tidak tersedia. Silakan pilih tanggal lain.'
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
                'Permintaan perpanjangan berhasil dikirim. Menunggu persetujuan mitra.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | MITRA INDEX
    |--------------------------------------------------------------------------
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
            compact('extension')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | APPROVE
    |--------------------------------------------------------------------------
    |
    | PENTING:
    |
    | Approve hanya mengubah status extension.
    |
    | Booking TIDAK diubah di sini.
    |
    | Booking baru diubah setelah customer membayar.
    |
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
            function () use ($extension) {

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
                    $lockedExtension->status !== 'pending'
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
                | STATUS BOOKING
                |--------------------------------------------------------------------------
                */

                if (!in_array(
                    $booking->status,
                    [
                        'pending',
                        'confirmed',
                    ]
                )) {
                    return false;
                }

                /*
                |--------------------------------------------------------------------------
                | PAYMENT BOOKING
                |--------------------------------------------------------------------------
                */

                if (
                    $booking->payment_status !== 'paid'
                ) {
                    return false;
                }

                /*
                |--------------------------------------------------------------------------
                | CEK CHECKOUT
                |--------------------------------------------------------------------------
                */

                $currentCheckOut =
                    Carbon::parse(
                        $booking->check_out
                    );

                $oldCheckOut =
                    Carbon::parse(
                        $lockedExtension->old_check_out
                    );

                $newCheckOut =
                    Carbon::parse(
                        $lockedExtension->new_check_out
                    );

                /*
                |--------------------------------------------------------------------------
                | CHECKOUT LAMA HARUS SAMA
                |--------------------------------------------------------------------------
                */

                if (
                    !$currentCheckOut->equalTo(
                        $oldCheckOut
                    )
                ) {
                    return false;
                }

                /*
                |--------------------------------------------------------------------------
                | CHECKOUT BARU
                |--------------------------------------------------------------------------
                */

                if (
                    !$newCheckOut->greaterThan(
                        $currentCheckOut
                    )
                ) {
                    return false;
                }

                /*
                |--------------------------------------------------------------------------
                | CEK BENTROK
                |--------------------------------------------------------------------------
                */

                $conflict = Booking::where(
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
                        $newCheckOut->format('Y-m-d')
                    )
                    ->where(
                        'check_out',
                        '>',
                        $currentCheckOut->format('Y-m-d')
                    )
                    ->exists();

                if ($conflict) {
                    return false;
                }

                /*
                |--------------------------------------------------------------------------
                | APPROVE EXTENSION
                |--------------------------------------------------------------------------
                */

                $lockedExtension->update([
                    'status' =>
                        'approved',

                    'payment_method' =>
                        'qris',

                    'payment_status' =>
                        'pending',

                    'payment_deadline' =>
                        now()->addHours(24),

                    'qris_transaction_id' =>
                        null,

                    'paid_at' =>
                        null,

                    'approved_at' =>
                        now(),
                ]);

                return true;
            }
        );

        /*
        |--------------------------------------------------------------------------
        | GAGAL
        |--------------------------------------------------------------------------
        */

        if (!$result) {

            return back()->with(
                'error',
                'Perpanjangan tidak dapat disetujui karena tanggal sudah tidak tersedia atau booking sudah berubah.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | BERHASIL
        |--------------------------------------------------------------------------
        */

        return back()->with(
            'success',
            'Perpanjangan berhasil disetujui. Customer sekarang dapat melakukan pembayaran.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | PAYMENT PAGE
    |--------------------------------------------------------------------------
    */

    public function payment(
        Request $request,
        BookingExtension $extension
    ) {
        abort_unless(
            $extension->booking->customer_id ===
            $request->user()->id,
            403,
            'Anda tidak berhak membayar perpanjangan ini.'
        );

        abort_unless(
            $extension->status === 'approved',
            400,
            'Perpanjangan belum disetujui oleh mitra.'
        );

        abort_unless(
            $extension->payment_status === 'pending',
            400,
            'Pembayaran perpanjangan ini sudah diproses.'
        );

        /*
        |--------------------------------------------------------------------------
        | CEK DEADLINE
        |--------------------------------------------------------------------------
        */

        if (
            $extension->payment_deadline &&
            now()->greaterThan(
                $extension->payment_deadline
            )
        ) {

            return redirect()
                ->route(
                    'customer.bookings.show',
                    $extension->booking
                )
                ->with(
                    'error',
                    'Batas waktu pembayaran perpanjangan sudah berakhir.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | LOAD DATA
        |--------------------------------------------------------------------------
        */

        $extension->load([
            'booking.property',
        ]);

        return view(
            'bookings.extensions.payment',
            compact('extension')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SIMULATE PAYMENT
    |--------------------------------------------------------------------------
    */

    public function simulatePay(
        Request $request,
        BookingExtension $extension
    ) {
        abort_unless(
            $extension->booking->customer_id ===
            $request->user()->id,
            403,
            'Anda tidak berhak membayar perpanjangan ini.'
        );

        abort_unless(
            $extension->status === 'approved',
            400,
            'Perpanjangan belum disetujui oleh mitra.'
        );

        abort_unless(
            $extension->payment_status === 'pending',
            400,
            'Pembayaran perpanjangan ini sudah diproses.'
        );

        $result = DB::transaction(
            function () use ($extension) {

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

                /*
                |--------------------------------------------------------------------------
                | SUDAH DIBAYAR
                |--------------------------------------------------------------------------
                */

                if (
                    $lockedExtension->payment_status === 'paid'
                ) {
                    return 'already_paid';
                }

                /*
                |--------------------------------------------------------------------------
                | STATUS
                |--------------------------------------------------------------------------
                */

                if (
                    $lockedExtension->status !== 'approved'
                ) {
                    return false;
                }

                /*
                |--------------------------------------------------------------------------
                | DEADLINE
                |--------------------------------------------------------------------------
                */

                if (
                    $lockedExtension->payment_deadline &&
                    now()->greaterThan(
                        $lockedExtension->payment_deadline
                    )
                ) {
                    return 'expired';
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
                | STATUS BOOKING
                |--------------------------------------------------------------------------
                */

                if (!in_array(
                    $booking->status,
                    [
                        'pending',
                        'confirmed',
                    ]
                )) {
                    return false;
                }

                /*
                |--------------------------------------------------------------------------
                | PAYMENT BOOKING
                |--------------------------------------------------------------------------
                */

                if (
                    $booking->payment_status !== 'paid'
                ) {
                    return false;
                }

                /*
                |--------------------------------------------------------------------------
                | TANGGAL
                |--------------------------------------------------------------------------
                */

                $currentCheckOut =
                    Carbon::parse(
                        $booking->check_out
                    );

                $oldCheckOut =
                    Carbon::parse(
                        $lockedExtension->old_check_out
                    );

                $newCheckOut =
                    Carbon::parse(
                        $lockedExtension->new_check_out
                    );

                /*
                |--------------------------------------------------------------------------
                | BOOKING TIDAK BOLEH BERUBAH
                |--------------------------------------------------------------------------
                */

                if (
                    !$currentCheckOut->equalTo(
                        $oldCheckOut
                    )
                ) {
                    return 'booking_changed';
                }

                /*
                |--------------------------------------------------------------------------
                | CHECKOUT BARU
                |--------------------------------------------------------------------------
                */

                if (
                    !$newCheckOut->greaterThan(
                        $currentCheckOut
                    )
                ) {
                    return false;
                }

                /*
                |--------------------------------------------------------------------------
                | CEK BENTROK TERAKHIR
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
                            $newCheckOut->format('Y-m-d')
                        )
                        ->where(
                            'check_out',
                            '>',
                            $currentCheckOut->format('Y-m-d')
                        )
                        ->exists();

                if ($conflict) {
                    return 'conflict';
                }

                /*
                |--------------------------------------------------------------------------
                | NOMINAL
                |--------------------------------------------------------------------------
                */

                $additionalAmount =
                    (float) $lockedExtension->additional_amount;

                $newSubtotal =
                    (float) $booking->subtotal +
                    $additionalAmount;

                /*
                |--------------------------------------------------------------------------
                | KOMISI
                |--------------------------------------------------------------------------
                */

                $commissionPercentage =
                    (float) $booking->commission_percentage;

                $newCommissionAmount =
                    round(
                        $newSubtotal *
                        ($commissionPercentage / 100),
                        2
                    );

                /*
                |--------------------------------------------------------------------------
                | MITRA PAYOUT
                |--------------------------------------------------------------------------
                */

                $newMitraPayout =
                    $newSubtotal -
                    $newCommissionAmount;

                /*
                |--------------------------------------------------------------------------
                | TOTAL
                |--------------------------------------------------------------------------
                */

                $newTotalPrice =
                    (float) $booking->total_price +
                    $additionalAmount;

                /*
                |--------------------------------------------------------------------------
                | UPDATE BOOKING
                |--------------------------------------------------------------------------
                */

                $booking->update([
                    'check_out' =>
                        $newCheckOut->format('Y-m-d'),

                    'subtotal' =>
                        $newSubtotal,

                    'commission_amount' =>
                        $newCommissionAmount,

                    'mitra_payout_amount' =>
                        $newMitraPayout,

                    'total_price' =>
                        $newTotalPrice,
                ]);

                /*
                |--------------------------------------------------------------------------
                | UPDATE EXTENSION
                |--------------------------------------------------------------------------
                */

                $transactionId =
                    'SIMULATED-EXT-' .
                    strtoupper(
                        substr(
                            md5(
                                uniqid(
                                    (string) $lockedExtension->id,
                                    true
                                )
                            ),
                            0,
                            12
                        )
                    );

                $lockedExtension->update([
                    'payment_method' =>
                        'qris',

                    'payment_status' =>
                        'paid',

                    'qris_transaction_id' =>
                        $transactionId,

                    'paid_at' =>
                        now(),
                ]);

                return 'paid';
            }
        );

        /*
        |--------------------------------------------------------------------------
        | HASIL PEMBAYARAN
        |--------------------------------------------------------------------------
        */

        if ($result === 'paid') {

            return redirect()
                ->route(
                    'customer.bookings.show',
                    $extension->booking
                )
                ->with(
                    'success',
                    'Pembayaran perpanjangan berhasil. Booking berhasil diperpanjang.'
                );
        }

        if ($result === 'already_paid') {

            return redirect()
                ->route(
                    'customer.bookings.show',
                    $extension->booking
                )
                ->with(
                    'success',
                    'Pembayaran perpanjangan sudah diproses.'
                );
        }

        if ($result === 'expired') {

            return redirect()
                ->route(
                    'customer.bookings.show',
                    $extension->booking
                )
                ->with(
                    'error',
                    'Batas waktu pembayaran perpanjangan sudah berakhir.'
                );
        }

        if ($result === 'booking_changed') {

            return redirect()
                ->route(
                    'customer.bookings.show',
                    $extension->booking
                )
                ->with(
                    'error',
                    'Pembayaran tidak dapat diproses karena tanggal booking sudah berubah.'
                );
        }

        if ($result === 'conflict') {

            return redirect()
                ->route(
                    'customer.bookings.show',
                    $extension->booking
                )
                ->with(
                    'error',
                    'Pembayaran tidak dapat diproses karena tanggal perpanjangan sudah tidak tersedia.'
                );
        }

        return redirect()
            ->route(
                'customer.bookings.show',
                $extension->booking
            )
            ->with(
                'error',
                'Pembayaran perpanjangan tidak dapat diproses.'
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

        $validated = $request->validate([
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
    | CUSTOMER CANCEL
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

        $user = $request->user();

        $booking = $extension->booking;

        $allowed =
            $user->hasRole('admin')
            ||
            $user->hasRole('super_admin')
            ||
            (
                $user->hasRole('customer')
                &&
                $booking->customer_id === $user->id
            )
            ||
            (
                $user->hasRole('mitra')
                &&
                optional($booking->property)->mitra_id === $user->id
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

        $booking = $extension->booking;

        abort_unless(
            $request->user()->hasRole('mitra')
            &&
            optional($booking->property)->mitra_id ===
            $request->user()->id,
            403,
            'Anda tidak berhak memproses permintaan ini.'
        );
    }
}