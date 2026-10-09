<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Menampilkan invoice / bukti pembayaran booking.
     *
     * Bisa digunakan oleh:
     * - Customer
     * - Mitra
     * - Admin
     * - Super Admin
     */
    public function show(Request $request, Booking $booking)
    {
        $this->authorizeAccess($request, $booking);

        $booking->load([
            'property',
            'property.mitra',
            'customer',
        ]);

        /*
        |--------------------------------------------------------------------------
        | STATUS PEMBAYARAN
        |--------------------------------------------------------------------------
        */

        $paymentStatus = $booking->payment_status;

        abort_unless(
            in_array(
                $paymentStatus,
                [
                    'dp_paid',
                    'paid',
                    'refunded',
                ],
                true
            ),
            400,
            'Invoice belum tersedia karena booking belum memiliki pembayaran.'
        );

        /*
        |--------------------------------------------------------------------------
        | JUMLAH PEMBAYARAN
        |--------------------------------------------------------------------------
        */

        $totalPrice = (float) ($booking->total_price ?? 0);

        $dpAmount = $booking->dp_amount !== null
            ? (float) $booking->dp_amount
            : round($totalPrice * 0.50, 2);

        $remainingAmount = $booking->remaining_amount !== null
            ? (float) $booking->remaining_amount
            : max($totalPrice - $dpAmount, 0);

        /*
        |--------------------------------------------------------------------------
        | TANGGAL PEMBAYARAN
        |--------------------------------------------------------------------------
        */

        $dpPaidAt = $booking->dp_paid_at
            ? Carbon::parse($booking->dp_paid_at)
            : null;

        $settlementPaidAt = $booking->settlement_paid_at
            ? Carbon::parse($booking->settlement_paid_at)
            : null;

        /*
        |--------------------------------------------------------------------------
        | TOTAL YANG SUDAH DIBAYAR
        |--------------------------------------------------------------------------
        */

        if ($paymentStatus === 'paid') {
            $totalPaid = $totalPrice;
        } elseif ($paymentStatus === 'refunded') {
            $totalPaid = $booking->refund_amount !== null
                ? max(
                    0,
                    $totalPrice - (float) $booking->refund_amount
                )
                : $dpAmount;
        } else {
            $totalPaid = $dpAmount;
        }

        /*
        |--------------------------------------------------------------------------
        | STATUS INVOICE
        |--------------------------------------------------------------------------
        */

        if ($paymentStatus === 'paid') {
            $invoiceStatus = 'LUNAS';
        } elseif ($paymentStatus === 'refunded') {
            $invoiceStatus = 'REFUNDED';
        } else {
            $invoiceStatus = 'DP TERBAYAR';
        }

        /*
        |--------------------------------------------------------------------------
        | NOMOR INVOICE
        |--------------------------------------------------------------------------
        |
        | Tidak perlu menambah kolom database.
        |
        */

        $invoiceNumber = 'INV-' .
            $booking->booking_code;

        return view(
            'invoices.show',
            compact(
                'booking',
                'invoiceNumber',
                'paymentStatus',
                'invoiceStatus',
                'totalPrice',
                'dpAmount',
                'remainingAmount',
                'totalPaid',
                'dpPaidAt',
                'settlementPaidAt'
            )
        );
    }

    /**
     * Authorization berdasarkan role.
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

        if (
            $user->hasRole('admin') ||
            $user->hasRole('super_admin')
        ) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | CUSTOMER
        |--------------------------------------------------------------------------
        */

        if (
            $user->hasRole('customer') &&
            (int) $booking->customer_id === (int) $user->id
        ) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | MITRA
        |--------------------------------------------------------------------------
        */

        if (
            $user->hasRole('mitra') &&
            $booking->property &&
            (int) $booking->property->mitra_id === (int) $user->id
        ) {
            return;
        }

        abort(
            403,
            'Anda tidak berhak mengakses invoice booking ini.'
        );
    }
}