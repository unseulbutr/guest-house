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
        | Jangan filter cancelled di sini.
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
        | ADMIN & SUPER ADMIN
        |--------------------------------------------------------------------------
        |
        | Tidak perlu filter.
        | Mereka dapat melihat seluruh booking.
        |
        */

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
        |
        | Contoh:
        |
        | check_in  = 20 September
        | check_out = 25 September
        |
        | Maka tanggal yang terisi:
        | 20, 21, 22, 23, 24
        |
        | Tanggal 25 adalah tanggal checkout dan tidak dihitung sebagai
        | malam yang ditempati.
        |
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
        |
        | Booking dianggap aktif pada tanggal:
        |
        | check_in <= selected_date
        | DAN
        | check_out > selected_date
        |
        | Jadi:
        |
        | 20 - 25
        |
        | akan muncul pada:
        | 20, 21, 22, 23, 24
        |
        | dan tidak muncul pada tanggal 25 karena 25 adalah checkout.
        |
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
        |
        | Hanya pending dan confirmed yang dianggap memakai tanggal.
        |
        | cancelled tidak menghalangi booking baru.
        |
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

                    'payment_method' =>
                        'qris',

                    'payment_status' =>
                        'pending',

                    'refund_status' =>
                        'none',

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
                'Booking dibuat, silakan selesaikan pembayaran QRIS.'
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

        if ($booking->payment_status === 'paid') {
            $booking->update([
                'refund_status' => 'pending',

                'refund_amount' =>
                    $booking->total_price,

                'refund_reason' =>
                    'Booking ditolak oleh mitra.',
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
        } else {
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
        | JIKA BELUM BAYAR
        |--------------------------------------------------------------------------
        */

        if ($booking->payment_status === 'pending') {
            $booking->update([
                'refund_status' => 'none',

                'refund_amount' => null,

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
        | JIKA SUDAH BAYAR
        |--------------------------------------------------------------------------
        */

        if ($booking->payment_status === 'paid') {
            $booking->update([
                'refund_status' => 'pending',

                'refund_amount' =>
                    $booking->total_price,

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
            $booking->payment_status === 'paid',
            400,
            'Booking ini belum memiliki pembayaran yang dapat direfund.'
        );

        abort_unless(
            $booking->refund_status === 'pending',
            400,
            'Refund booking ini tidak sedang menunggu proses.'
        );

        $booking->update([
            'payment_status' => 'refunded',

            'refund_status' => 'completed',

            'refund_amount' =>
                $booking->refund_amount
                ?? $booking->total_price,

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
                $booking->refund_amount
                ?? $booking->total_price,
                0,
                ',',
                '.'
            ) .
            '.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RESOLVE STATUS AFTER PAYMENT
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

        $booking->update([
            'payment_status' => 'paid',

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
    | SIMULATE PAYMENT
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

        $booking->update([
            'payment_status' => 'paid',

            'status' =>
                $this->resolveStatusAfterPayment(
                    $booking
                ),

            'qris_transaction_id' =>
                'SIMULATED-' .
                strtoupper(
                    uniqid()
                ),
        ]);

        $message =
            $booking->property->management_type === 'dikelola'
                ? '(Simulasi) Pembayaran QRIS berhasil, booking dikonfirmasi otomatis.'
                : '(Simulasi) Pembayaran QRIS berhasil. Menunggu konfirmasi dari mitra properti.';

        return back()->with(
            'success',
            $message
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