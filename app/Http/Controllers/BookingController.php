<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Property;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $user = $request->user();

        /*
        |--------------------------------------------------------------------------
        | MONTH
        |--------------------------------------------------------------------------
        */

        try {
            $month = $request->filled('month')
                ? Carbon::createFromFormat(
                    'Y-m',
                    $request->month
                )->startOfMonth()
                : now()->startOfMonth();
        } catch (\Exception $e) {
            $month = now()->startOfMonth();
        }

        /*
        |--------------------------------------------------------------------------
        | SELECTED DATE
        |--------------------------------------------------------------------------
        */

        try {
            $selectedDate = $request->filled('date')
                ? Carbon::createFromFormat(
                    'Y-m-d',
                    $request->date
                )
                : (
                    $month->isSameMonth(now())
                        ? now()->startOfDay()
                        : $month->copy()->startOfDay()
                );
        } catch (\Exception $e) {
            $selectedDate = $month->copy()->startOfDay();
        }

        /*
        |--------------------------------------------------------------------------
        | PASTIKAN TANGGAL MASIH DALAM BULAN YANG DIPILIH
        |--------------------------------------------------------------------------
        */

        if (!$selectedDate->isSameMonth($month)) {
            $selectedDate = $month->copy()->startOfDay();
        }

        $monthStart = $month->copy()->startOfMonth();
        $monthEnd = $month->copy()->endOfMonth();

        /*
        |--------------------------------------------------------------------------
        | BASE QUERY SESUAI ROLE
        |--------------------------------------------------------------------------
        |
        | Booking cancelled tetap terlihat karena bisa memiliki refund.
        |
        */

        $baseQuery = Booking::query();

        /*
        |--------------------------------------------------------------------------
        | CUSTOMER
        |--------------------------------------------------------------------------
        */

        if ($user->hasRole('customer')) {
            $baseQuery->where(
                'customer_id',
                $user->id
            );
        }

        /*
        |--------------------------------------------------------------------------
        | MITRA
        |--------------------------------------------------------------------------
        */

        elseif ($user->hasRole('mitra')) {
            $baseQuery->whereHas(
                'property',
                function ($query) use ($user) {
                    $query->where(
                        'mitra_id',
                        $user->id
                    );
                }
            );
        }

        /*
        |--------------------------------------------------------------------------
        | BOOKING DALAM BULAN
        |--------------------------------------------------------------------------
        */

        $monthlyBookings = (clone $baseQuery)
            ->with([
                'property',
                'customer',
            ])
            ->whereDate(
                'check_in',
                '<',
                $monthEnd->copy()->addDay()->format('Y-m-d')
            )
            ->whereDate(
                'check_out',
                '>',
                $monthStart->format('Y-m-d')
            )
            ->orderBy('check_in')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | JUMLAH BOOKING PER TANGGAL
        |--------------------------------------------------------------------------
        */

        $bookingCounts = [];

        foreach ($monthlyBookings as $booking) {
            $start = Carbon::parse(
                $booking->check_in
            )->startOfDay();

            $end = Carbon::parse(
                $booking->check_out
            )->startOfDay();

            while ($start->lt($end)) {
                if (
                    $start->gte($monthStart)
                    &&
                    $start->lte($monthEnd)
                ) {
                    $dateKey = $start->format('Y-m-d');

                    $bookingCounts[$dateKey] =
                        ($bookingCounts[$dateKey] ?? 0) + 1;
                }

                $start->addDay();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | BOOKING PADA TANGGAL YANG DIPILIH
        |--------------------------------------------------------------------------
        */

        $selectedBookings = (clone $baseQuery)
            ->with([
                'property',
                'customer',
            ])
            ->whereDate(
                'check_in',
                '<=',
                $selectedDate->format('Y-m-d')
            )
            ->whereDate(
                'check_out',
                '>',
                $selectedDate->format('Y-m-d')
            )
            ->orderBy('check_in')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | CALENDAR RANGE
        |--------------------------------------------------------------------------
        */

        $calendarStart = $month
            ->copy()
            ->startOfMonth()
            ->startOfWeek(Carbon::MONDAY);

        $calendarEnd = $month
            ->copy()
            ->endOfMonth()
            ->endOfWeek(Carbon::SUNDAY);

        /*
        |--------------------------------------------------------------------------
        | TOTAL BOOKING BULAN INI
        |--------------------------------------------------------------------------
        */

        $totalMonthlyBookings = $monthlyBookings->count();

        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'bookings.index',
            compact(
                'monthlyBookings',
                'selectedBookings',
                'bookingCounts',
                'month',
                'selectedDate',
                'calendarStart',
                'calendarEnd',
                'totalMonthlyBookings'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(Property $property)
    {
        return view(
            'bookings.create',
            compact('property')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',

            'check_in' => 'required|date|after_or_equal:today',

            'check_out' => 'required|date|after:check_in',

            'guest_count' => 'required|integer|min:1',

            /*
            |--------------------------------------------------------------------------
            | DATA TAMU
            |--------------------------------------------------------------------------
            */
            'guest_name' => 'required|string|max:255',
            'guest_phone' => 'required|string|max:20',
            'guest_email' => 'nullable|email|max:255',
            'guest_address' => 'nullable|string|max:1000',

            /*
            |--------------------------------------------------------------------------
            | WAJIB MENYETUJUI SYARAT BOOKING
            |--------------------------------------------------------------------------
            */

            'terms_accepted' => 'accepted',
        ], [
            'terms_accepted.accepted' =>
                'Anda harus menyetujui Syarat & Ketentuan booking.',
        ]);

        /*
        |--------------------------------------------------------------------------
        | CEK PROPERTY
        |--------------------------------------------------------------------------
        */

        $property = Property::findOrFail(
            $validated['property_id']
        );

        /*
        |--------------------------------------------------------------------------
        | CEK BENTROK BOOKING
        |--------------------------------------------------------------------------
        */

        $isBooked = Booking::where(
            'property_id',
            $property->id
        )
            ->whereIn(
                'status',
                [
                    'pending',
                    'confirmed',
                ]
            )
            ->where(function ($query) use ($validated) {
                $query
                    ->where(
                        'check_in',
                        '<',
                        $validated['check_out']
                    )
                    ->where(
                        'check_out',
                        '>',
                        $validated['check_in']
                    );
            })
            ->exists();

        if ($isBooked) {
            return redirect()
                ->route(
                    'customer.bookings.create',
                    $property->id
                )
                ->withInput()
                ->with(
                    'booking_error',
                    'Property sudah dibooking pada tanggal yang Anda pilih.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | TRANSACTION
        |--------------------------------------------------------------------------
        */

        $booking = DB::transaction(
            function () use (
                $validated,
                $request
            ) {
                /*
                |--------------------------------------------------------------------------
                | LOCK PROPERTY
                |--------------------------------------------------------------------------
                */

                $property = Property::where(
                    'id',
                    $validated['property_id']
                )
                    ->lockForUpdate()
                    ->firstOrFail();

                /*
                |--------------------------------------------------------------------------
                | CEK ULANG BENTROK
                |--------------------------------------------------------------------------
                */

                $isBooked = Booking::where(
                    'property_id',
                    $property->id
                )
                    ->whereIn(
                        'status',
                        [
                            'pending',
                            'confirmed',
                        ]
                    )
                    ->where(function ($query) use ($validated) {
                        $query
                            ->where(
                                'check_in',
                                '<',
                                $validated['check_out']
                            )
                            ->where(
                                'check_out',
                                '>',
                                $validated['check_in']
                            );
                    })
                    ->exists();

                if ($isBooked) {
                    return null;
                }

                /*
                |--------------------------------------------------------------------------
                | HITUNG HARGA
                |--------------------------------------------------------------------------
                */

                $pricing = Booking::calculatePricing(
                    $property,
                    $validated['check_in'],
                    $validated['check_out']
                );

                /*
                |--------------------------------------------------------------------------
                | HITUNG DP 50%
                |--------------------------------------------------------------------------
                */

                $totalPrice = (float) $pricing['total_price'];

                $dpAmount = round(
                    $totalPrice * 0.50,
                    2
                );

                $remainingAmount = round(
                    $totalPrice - $dpAmount,
                    2
                );

                /*
                |--------------------------------------------------------------------------
                | DEADLINE PELUNASAN
                |--------------------------------------------------------------------------
                |
                | Pelunasan dapat dilakukan kapan saja setelah DP berhasil
                | dan wajib selesai sebelum tanggal check-in.
                |
                */

                $settlementDeadline = Carbon::parse(
                    $validated['check_in']
                )->startOfDay();

                /*
                |--------------------------------------------------------------------------
                | CREATE BOOKING
                |--------------------------------------------------------------------------
                */

                return Booking::create([
                    'customer_id' =>
                        $request->user()->id,

                    'property_id' =>
                        $property->id,

                    'check_in' =>
                        $validated['check_in'],

                    'check_out' =>
                        $validated['check_out'],

                    'guest_count' =>
                        $validated['guest_count'],

                    /*
                    |--------------------------------------------------------------------------
                    | DATA TAMU
                    |--------------------------------------------------------------------------
                    */
                    'guest_name' =>
                        $validated['guest_name'],

                    'guest_phone' =>
                        $validated['guest_phone'],

                    'guest_email' =>
                        $validated['guest_email'] ?? null,

                    'guest_address' =>
                        $validated['guest_address'] ?? null,

                    'subtotal' =>
                        $pricing['subtotal'],

                    'commission_percentage' =>
                        $pricing['commission_percentage'],

                    'commission_amount' =>
                        $pricing['commission_amount'],

                    'mitra_payout_amount' =>
                        $pricing['mitra_payout_amount'],

                    'total_price' =>
                        $pricing['total_price'],

                    /*
                    |--------------------------------------------------------------------------
                    | PAYMENT
                    |--------------------------------------------------------------------------
                    */

                    'payment_method' =>
                        'qris',

                    'payment_status' =>
                        'pending',

                    'dp_amount' =>
                        $dpAmount,

                    'remaining_amount' =>
                        $remainingAmount,

                    'settlement_deadline' =>
                        $settlementDeadline,

                    'terms_accepted_at' =>
                        now(),

                    /*
                    |--------------------------------------------------------------------------
                    | REFUND
                    |--------------------------------------------------------------------------
                    */

                    'refund_status' =>
                        'none',

                    'refund_amount' =>
                        null,

                    'cancellation_fee_amount' =>
                        null,

                    /*
                    |--------------------------------------------------------------------------
                    | STATUS
                    |--------------------------------------------------------------------------
                    */

                    'status' =>
                        'pending',
                ]);
            }
        );

        /*
        |--------------------------------------------------------------------------
        | BENTROK
        |--------------------------------------------------------------------------
        */

        if (!$booking) {
            return redirect()
                ->route(
                    'customer.bookings.create',
                    $property->id
                )
                ->withInput()
                ->with(
                    'booking_error',
                    'Property sudah dibooking pada tanggal yang Anda pilih.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | REDIRECT KE DETAIL BOOKING
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'customer.bookings.show',
                $booking
            )
            ->with(
                'success',
                'Booking berhasil dibuat. Silakan bayar DP 50% untuk melanjutkan.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(
        Request $request,
        Booking $booking
    ) {
        $this->authorizeAccess(
            $request,
            $booking
        );

        $booking->load([
            'property',
            'property.mitra',
            'customer',
            'extensions',
            'review',
        ]);

        return view(
            'bookings.show',
            compact('booking')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | MITRA CONFIRM
    |--------------------------------------------------------------------------
    */

    public function confirm(
        Request $request,
        Booking $booking
    ) {
        $this->authorizeMitraOwnsBooking(
            $request,
            $booking
        );

        abort_unless(
            $booking->status === 'pending',
            400,
            'Booking ini sudah diproses sebelumnya.'
        );

        abort_unless(
            in_array(
                $booking->payment_status,
                [
                    'dp_paid',
                    'paid',
                ]
            ),
            400,
            'Booking belum memiliki pembayaran DP.'
        );

        $booking->update([
            'status' => 'confirmed',
        ]);

        return back()->with(
            'success',
            'Booking #' .
            $booking->booking_code .
            ' dikonfirmasi.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | MITRA REJECT
    |--------------------------------------------------------------------------
    */

    public function reject(
        Request $request,
        Booking $booking
    ) {
        $this->authorizeMitraOwnsBooking(
            $request,
            $booking
        );

        abort_unless(
            $booking->status === 'pending',
            400,
            'Booking ini sudah diproses sebelumnya.'
        );

        $booking->update([
            'status' => 'cancelled',

            'rejected_by_mitra' => true,
        ]);

        /*
        |--------------------------------------------------------------------------
        | JIKA SUDAH BAYAR DP
        |--------------------------------------------------------------------------
        |
        | Jika Mitra yang menolak booking, DP dikembalikan penuh.
        |
        */

        if ($booking->payment_status === 'dp_paid') {
            $refundAmount = (float) (
                $booking->dp_amount
                ?? ($booking->total_price * 0.50)
            );

            $booking->update([
                'refund_status' => 'pending',

                'refund_amount' =>
                    $refundAmount,

                'cancellation_fee_amount' =>
                    0,

                'refund_reason' =>
                    'Booking ditolak oleh mitra. DP dikembalikan penuh.',
            ]);

            $message =
                'Booking ditolak. Refund DP sebesar Rp ' .
                number_format(
                    $refundAmount,
                    0,
                    ',',
                    '.'
                ) .
                ' menunggu diproses.';
        }

        /*
        |--------------------------------------------------------------------------
        | JIKA SUDAH LUNAS
        |--------------------------------------------------------------------------
        */

        elseif ($booking->payment_status === 'paid') {
            $booking->update([
                'refund_status' => 'pending',

                'refund_amount' =>
                    $booking->total_price,

                'cancellation_fee_amount' =>
                    0,

                'refund_reason' =>
                    'Booking ditolak oleh mitra. Refund penuh.',
            ]);

            $message =
                'Booking ditolak. Refund sebesar Rp ' .
                number_format(
                    $booking->total_price,
                    0,
                    ',',
                    '.'
                ) .
                ' menunggu diproses.';
        }

        /*
        |--------------------------------------------------------------------------
        | BELUM BAYAR
        |--------------------------------------------------------------------------
        */

        else {
            $message =
                'Booking #' .
                $booking->booking_code .
                ' ditolak.';
        }

        return back()->with(
            'success',
            $message
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CUSTOMER CANCEL
    |--------------------------------------------------------------------------
    */

    public function cancel(
        Request $request,
        Booking $booking
    ) {
        abort_unless(
            $booking->customer_id ===
            $request->user()->id,
            403,
            'Anda tidak berhak membatalkan booking ini.'
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
            'Booking berstatus "' .
            $booking->status .
            '" tidak bisa dibatalkan.'
        );

        $booking->update([
            'status' => 'cancelled',
        ]);

        /*
        |--------------------------------------------------------------------------
        | BELUM BAYAR
        |--------------------------------------------------------------------------
        */

        if ($booking->payment_status === 'pending') {
            $booking->update([
                'refund_status' => 'none',

                'refund_amount' => null,

                'cancellation_fee_amount' => null,

                'refund_reason' =>
                    'Booking dibatalkan customer sebelum pembayaran.',
            ]);

            return back()->with(
                'success',
                'Booking berhasil dibatalkan.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SUDAH BAYAR DP
        |--------------------------------------------------------------------------
        |
        | Biaya pembatalan berdasarkan jarak dengan tanggal check-in:
        |
        | >= 7 hari  = 0%
        | 3-6 hari   = 5%
        | H-2        = 10%
        | H-1        = 15%
        | Hari H     = 20%
        |
        */

        if ($booking->payment_status === 'dp_paid') {
            $dpAmount = (float) (
                $booking->dp_amount
                ?? ($booking->total_price * 0.50)
            );

            $checkIn = Carbon::parse(
                $booking->check_in
            )->startOfDay();

            $today = now()->startOfDay();

            /*
            |--------------------------------------------------------------------------
            | CEK BATAS PEMBATALAN
            |--------------------------------------------------------------------------
            |
            | Pembatalan masih diperbolehkan sebelum waktu check-in.
            |
            */

            abort_unless(
                now()->lt($checkIn),
                400,
                'Booking tidak dapat dibatalkan setelah waktu check-in.'
            );

            $daysBeforeCheckIn = $today->diffInDays(
                $checkIn,
                false
            );

            /*
            |--------------------------------------------------------------------------
            | TENTUKAN PERSENTASE BIAYA PEMBATALAN
            |--------------------------------------------------------------------------
            */

            if ($daysBeforeCheckIn >= 7) {
                $cancellationPercentage = 0;
            } elseif ($daysBeforeCheckIn >= 3) {
                $cancellationPercentage = 5;
            } elseif ($daysBeforeCheckIn === 2) {
                $cancellationPercentage = 10;
            } elseif ($daysBeforeCheckIn === 1) {
                $cancellationPercentage = 15;
            } else {
                // Hari H, tetapi masih sebelum waktu check-in.
                $cancellationPercentage = 20;
            }

            /*
            |--------------------------------------------------------------------------
            | HITUNG BIAYA PEMBATALAN & REFUND
            |--------------------------------------------------------------------------
            */

            $cancellationFee = round(
                $dpAmount * ($cancellationPercentage / 100),
                2
            );

            $refundAmount = round(
                $dpAmount - $cancellationFee,
                2
            );

            $booking->update([
                'refund_status' =>
                    'pending',

                'refund_amount' =>
                    $refundAmount,

                'cancellation_fee_amount' =>
                    $cancellationFee,

                'refund_reason' =>
                    'Customer membatalkan booking. Biaya pembatalan ' .
                    $cancellationPercentage .
                    '% dari DP.',
            ]);

            return back()->with(
                'success',
                'Booking dibatalkan. Refund sebesar Rp ' .
                number_format(
                    $refundAmount,
                    0,
                    ',',
                    '.'
                ) .
                ' menunggu diproses. Biaya pembatalan sebesar ' .
                $cancellationPercentage .
                '% (Rp ' .
                number_format(
                    $cancellationFee,
                    0,
                    ',',
                    '.'
                ) .
                ').'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SUDAH LUNAS
        |--------------------------------------------------------------------------
        */

        if ($booking->payment_status === 'paid') {
            $booking->update([
                'refund_status' => 'pending',

                'refund_amount' =>
                    $booking->total_price,

                'cancellation_fee_amount' =>
                    0,

                'refund_reason' =>
                    'Customer membatalkan booking.',
            ]);

            return back()->with(
                'success',
                'Booking dibatalkan. Refund sedang diproses.'
            );
        }

        return back()->with(
            'success',
            'Booking berhasil dibatalkan.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN PROCESS REFUND
    |--------------------------------------------------------------------------
    */

    public function processRefund(
        Request $request,
        Booking $booking
    ) {
        abort_unless(
            $request->user()->hasRole('admin')
            ||
            $request->user()->hasRole('super_admin'),
            403,
            'Anda tidak berhak memproses refund.'
        );

        abort_unless(
            $booking->status === 'cancelled',
            400,
            'Booking belum dibatalkan.'
        );

        abort_unless(
            in_array(
                $booking->payment_status,
                [
                    'dp_paid',
                    'paid',
                ]
            ),
            400,
            'Booking ini belum memiliki pembayaran yang dapat direfund.'
        );

        abort_unless(
            $booking->refund_status === 'pending',
            400,
            'Refund booking ini tidak sedang menunggu proses.'
        );

        $refundAmount = $booking->refund_amount;

        /*
        |--------------------------------------------------------------------------
        | FALLBACK UNTUK DATA LAMA
        |--------------------------------------------------------------------------
        */

        if ($refundAmount === null) {
            if ($booking->payment_status === 'dp_paid') {
                $refundAmount =
                    $booking->dp_amount
                    ?? ($booking->total_price * 0.50);
            } else {
                $refundAmount =
                    $booking->total_price;
            }
        }

        $booking->update([
            'payment_status' => 'refunded',

            'refund_status' => 'completed',

            'refund_amount' =>
                $refundAmount,

            'refund_transaction_id' =>
                'REFUND-' .
                strtoupper(
                    uniqid()
                ),

            'refunded_at' => now(),
        ]);

        return back()->with(
            'success',
            'Refund berhasil diproses sebesar Rp ' .
            number_format(
                $refundAmount,
                0,
                ',',
                '.'
            ) .
            '.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RESOLVE STATUS AFTER DP
    |--------------------------------------------------------------------------
    */

    protected function resolveStatusAfterDpPayment(
        Booking $booking
    ): string {
        /*
        |--------------------------------------------------------------------------
        | DIKELOLA
        |--------------------------------------------------------------------------
        |
        | Properti yang dikelola sistem dapat langsung confirmed
        | setelah DP berhasil.
        |
        */

        if (
            $booking->property->management_type === 'dikelola'
        ) {
            return 'confirmed';
        }

        /*
        |--------------------------------------------------------------------------
        | MANDIRI
        |--------------------------------------------------------------------------
        |
        | Tetap pending sampai Mitra melakukan konfirmasi.
        |
        */

        return 'pending';
    }

    /*
    |--------------------------------------------------------------------------
    | RESOLVE STATUS AFTER FULL PAYMENT
    |--------------------------------------------------------------------------
    */

    protected function resolveStatusAfterPayment(
        Booking $booking
    ): string {
        return $booking->property->management_type === 'dikelola'
            ? 'confirmed'
            : 'pending';
    }

    /*
    |--------------------------------------------------------------------------
    | WEBHOOK PAYMENT
    |--------------------------------------------------------------------------
    |
    | payment_stage:
    |
    | dp         = pembayaran DP 50%
    | settlement = pelunasan 50%
    | full       = pembayaran penuh/legacy
    |
    */

    public function markAsPaid(
        Request $request,
        Booking $booking
    ) {
        abort_unless(
            $request->header('X-Webhook-Secret')
            ===
            config(
                'services.payment_gateway.webhook_secret'
            ),
            403,
            'Webhook signature tidak valid.'
        );

        abort_if(
            $booking->status === 'cancelled',
            400,
            'Booking sudah dibatalkan.'
        );

        $paymentStage = $request->input(
            'payment_stage',
            'full'
        );

        /*
        |--------------------------------------------------------------------------
        | WEBHOOK DP
        |--------------------------------------------------------------------------
        */

        if ($paymentStage === 'dp') {
            abort_unless(
                $booking->payment_status === 'pending',
                400,
                'Booking ini tidak sedang menunggu pembayaran DP.'
            );

            $dpAmount = (float) (
                $booking->dp_amount
                ?? ($booking->total_price * 0.50)
            );

            $remainingAmount = round(
                (float) $booking->total_price - $dpAmount,
                2
            );

            $booking->update([
                'payment_status' => 'dp_paid',

                'dp_amount' =>
                    $dpAmount,

                'dp_paid_at' =>
                    now(),

                'remaining_amount' =>
                    $remainingAmount,

                'settlement_deadline' =>
                    $booking->settlement_deadline
                    ??
                    Carbon::parse(
                        $booking->check_in
                    )->startOfDay(),

                'status' =>
                    $this->resolveStatusAfterDpPayment(
                        $booking
                    ),

                'qris_transaction_id' =>
                    $request->input(
                        'transaction_id'
                    ),
            ]);

            return response()->json([
                'message' =>
                    'Pembayaran DP dikonfirmasi.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | WEBHOOK PELUNASAN
        |--------------------------------------------------------------------------
        */

        if ($paymentStage === 'settlement') {
            abort_unless(
                $booking->payment_status === 'dp_paid',
                400,
                'Booking belum berada pada tahap pelunasan.'
            );

            abort_unless(
                $booking->status === 'confirmed',
                400,
                'Booking belum dikonfirmasi mitra.'
            );

            $settlementDeadline =
                $booking->settlement_deadline
                ??
                Carbon::parse(
                    $booking->check_in
                )->startOfDay();

            abort_unless(
                now()->lessThan(
                    Carbon::parse($booking->check_in)->startOfDay()
                ),
                400,
                'Pelunasan harus diselesaikan sebelum tanggal check-in.'
            );

            $booking->update([
                'payment_status' => 'paid',

                'remaining_amount' =>
                    0,

                'settlement_paid_at' =>
                    now(),

                'qris_transaction_id' =>
                    $request->input(
                        'transaction_id'
                    ),
            ]);

            return response()->json([
                'message' =>
                    'Pelunasan booking dikonfirmasi.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | LEGACY / FULL PAYMENT
        |--------------------------------------------------------------------------
        |
        | Tetap dipertahankan agar webhook lama tidak langsung rusak.
        |
        */

        $booking->update([
            'payment_status' => 'paid',

            'remaining_amount' =>
                0,

            'settlement_paid_at' =>
                now(),

            'status' =>
                $this->resolveStatusAfterPayment(
                    $booking
                ),

            'qris_transaction_id' =>
                $request->input(
                    'transaction_id'
                ),
        ]);

        return response()->json([
            'message' =>
                'Pembayaran dikonfirmasi.',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | SIMULATE DP PAYMENT
    |--------------------------------------------------------------------------
    */

    public function simulatePay(
        Request $request,
        Booking $booking
    ) {
        abort_unless(
            $booking->customer_id ===
            $request->user()->id,
            403
        );

        abort_unless(
            $booking->status !== 'cancelled',
            400,
            'Booking sudah dibatalkan.'
        );

        abort_unless(
            $booking->payment_status === 'pending',
            400,
            'Booking ini sudah dibayar/diproses.'
        );

        $dpAmount = (float) (
            $booking->dp_amount
            ?? ($booking->total_price * 0.50)
        );

        $remainingAmount = round(
            (float) $booking->total_price - $dpAmount,
            2
        );

        $settlementDeadline =
            $booking->settlement_deadline
            ??
            Carbon::parse(
                $booking->check_in
            )
                ->startOfDay();

        $booking->update([
            'payment_status' => 'dp_paid',

            'dp_amount' =>
                $dpAmount,

            'dp_paid_at' =>
                now(),

            'remaining_amount' =>
                $remainingAmount,

            'settlement_deadline' =>
                $settlementDeadline,

            'status' =>
                $this->resolveStatusAfterDpPayment(
                    $booking
                ),

            'qris_transaction_id' =>
                'DP-SIMULATED-' .
                strtoupper(
                    uniqid()
                ),
        ]);

        $message =
            $booking->property->management_type === 'dikelola'
                ? '(Simulasi) Pembayaran DP 50% berhasil. Booking dikonfirmasi otomatis.'
                : '(Simulasi) Pembayaran DP 50% berhasil. Menunggu konfirmasi dari mitra properti.';

        return back()->with(
            'success',
            $message
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SIMULATE SETTLEMENT / PELUNASAN
    |--------------------------------------------------------------------------
    */

    public function simulateSettlement(
        Request $request,
        Booking $booking
    ) {
        abort_unless(
            $booking->customer_id ===
            $request->user()->id,
            403,
            'Anda tidak berhak membayar booking ini.'
        );

        abort_unless(
            $booking->status !== 'cancelled',
            400,
            'Booking sudah dibatalkan.'
        );

        abort_unless(
            $booking->payment_status === 'dp_paid',
            400,
            'Booking belum membayar DP.'
        );

        abort_unless(
            $booking->status === 'confirmed',
            400,
            'Booking belum dikonfirmasi oleh mitra.'
        );

        /*
        |--------------------------------------------------------------------------
        | WAKTU PELUNASAN
        |--------------------------------------------------------------------------
        |
        | Pelunasan dapat dilakukan kapan saja setelah DP berhasil.
        | H-1 hanya digunakan sebagai waktu pengingat, bukan awal
        | dibukanya pelunasan.
        |
        */

        $checkIn = Carbon::parse(
            $booking->check_in
        );

        $settlementDeadline =
            $booking->settlement_deadline
            ??
            $checkIn->copy()->startOfDay();

        /*
        |--------------------------------------------------------------------------
        | SUDAH MASUK TANGGAL CHECK-IN
        |--------------------------------------------------------------------------
        */

        abort_unless(
            now()->lessThan(
                $checkIn->copy()->startOfDay()
            ),
            400,
            'Pelunasan harus diselesaikan sebelum tanggal check-in.'
        );

        $booking->update([
            'payment_status' => 'paid',

            'remaining_amount' =>
                0,

            'settlement_paid_at' =>
                now(),

            'qris_transaction_id' =>
                'SETTLEMENT-SIMULATED-' .
                strtoupper(
                    uniqid()
                ),
        ]);

        return back()->with(
            'success',
            '(Simulasi) Pelunasan 50% berhasil. Booking sekarang sudah lunas.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | AUTHORIZE BOOKING
    |--------------------------------------------------------------------------
    */

    protected function authorizeAccess(
        Request $request,
        Booking $booking
    ): void {
        $user = $request->user();

        /*
        |--------------------------------------------------------------------------
        | ADMIN / SUPER ADMIN
        |--------------------------------------------------------------------------
        */

        $allowed =
            $user->hasRole('admin')
            ||
            $user->hasRole('super_admin');

        /*
        |--------------------------------------------------------------------------
        | CUSTOMER
        |--------------------------------------------------------------------------
        */

        if (
            $user->hasRole('customer')
            &&
            $booking->customer_id === $user->id
        ) {
            $allowed = true;
        }

        /*
        |--------------------------------------------------------------------------
        | MITRA
        |--------------------------------------------------------------------------
        */

        if (
            $user->hasRole('mitra')
            &&
            $booking->property->mitra_id === $user->id
        ) {
            $allowed = true;
        }

        abort_unless(
            $allowed,
            403,
            'Anda tidak berhak mengakses booking ini.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | AUTHORIZE MITRA
    |--------------------------------------------------------------------------
    */

    protected function authorizeMitraOwnsBooking(
        Request $request,
        Booking $booking
    ): void {
        $user = $request->user();

        abort_unless(
            $user->hasRole('mitra')
            &&
            $booking->property->mitra_id === $user->id,
            403,
            'Anda tidak berhak memproses booking ini.'
        );
    }
}