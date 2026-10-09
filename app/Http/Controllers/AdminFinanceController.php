<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\MitraEarning;
use App\Models\MitraWithdrawal;
use Illuminate\Http\Request;

class AdminFinanceController extends Controller
{
    public function index(
        Request $request
    ) {
        $paidBookings =
            Booking::query()
                ->whereIn(
                    'payment_status',
                    [
                        'dp_paid',
                        'paid',
                        'refunded',
                    ]
                );

        $grossCollected =
            (float)
            (clone $paidBookings)
                ->get()
                ->sum(
                    function (
                        Booking $booking
                    ) {

                        if (
                            $booking
                                ->payment_status
                            === 'paid'
                        ) {
                            return (float)
                                $booking
                                    ->total_price;
                        }

                        return (float) (
                            $booking
                                ->dp_amount
                            ?? 0
                        );
                    }
                );

        $refunds =
            (float)
            Booking::query()
                ->where(
                    'refund_status',
                    'completed'
                )
                ->sum(
                    'refund_amount'
                );

        $platformCommission =
            (float)
            Booking::query()
                ->whereIn(
                    'payment_status',
                    [
                        'dp_paid',
                        'paid',
                    ]
                )
                ->sum(
                    'commission_amount'
                );

        $mitraLedger =
            (float)
            MitraEarning::sum(
                'amount'
            );

        $pendingWithdrawals =
            (float)
            MitraWithdrawal::query()
                ->whereIn(
                    'status',
                    [
                        'pending',
                        'approved',
                    ]
                )
                ->sum(
                    'amount'
                );

        $paidWithdrawals =
            (float)
            MitraWithdrawal::query()
                ->where(
                    'status',
                    'paid'
                )
                ->sum(
                    'amount'
                );

        $withdrawals =
            MitraWithdrawal::query()
                ->with('mitra')
                ->latest()
                ->paginate(15);

        return view(
            'admin.finance.index',
            compact(
                'grossCollected',
                'refunds',
                'platformCommission',
                'mitraLedger',
                'pendingWithdrawals',
                'paidWithdrawals',
                'withdrawals'
            )
        );
    }

    public function approveWithdrawal(
        Request $request,
        MitraWithdrawal $withdrawal
    ) {
        abort_unless(
            $withdrawal->status === 'pending',
            400,
            'Pencairan ini sudah diproses.'
        );

        $withdrawal->update([
            'status' =>
                'approved',

            'processed_by' =>
                $request->user()->id,

            'approved_at' =>
                now(),
        ]);

        return back()->with(
            'success',
            'Permintaan pencairan Mitra disetujui. Silakan lakukan transfer/pembayaran.'
        );
    }

    public function markAsPaid(
        Request $request,
        MitraWithdrawal $withdrawal
    ) {
        abort_unless(
            in_array(
                $withdrawal->status,
                [
                    'pending',
                    'approved',
                ],
                true
            ),
            400,
            'Pencairan ini tidak dapat ditandai sebagai dibayar.'
        );

        $withdrawal->update([
            'status' =>
                'paid',

            'processed_by' =>
                $request->user()->id,

            'paid_at' =>
                now(),
        ]);

        return back()->with(
            'success',
            'Pencairan Mitra sebesar Rp ' .
            number_format(
                $withdrawal->amount,
                0,
                ',',
                '.'
            ) .
            ' ditandai sudah dibayar.'
        );
    }

    public function rejectWithdrawal(
        Request $request,
        MitraWithdrawal $withdrawal
    ) {
        abort_unless(
            in_array(
                $withdrawal->status,
                [
                    'pending',
                    'approved',
                ],
                true
            ),
            400,
            'Pencairan ini tidak dapat ditolak.'
        );

        $validated =
            $request->validate([
                'admin_note' => [
                    'nullable',
                    'string',
                    'max:1000',
                ],
            ]);

        $withdrawal->update([
            'status' =>
                'rejected',

            'processed_by' =>
                $request->user()->id,

            'rejected_at' =>
                now(),

            'admin_note' =>
                $validated['admin_note']
                ?? null,
        ]);

        return back()->with(
            'success',
            'Permintaan pencairan ditolak.'
        );
    }
}