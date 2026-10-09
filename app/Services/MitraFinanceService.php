<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\MitraEarning;

class MitraFinanceService
{
    /**
     * Mencatat hak pendapatan Mitra ketika uang customer benar-benar masuk.
     *
     * Uang tetap berada di platform/Super Admin.
     * Record ini hanya mencatat hak pendapatan Mitra.
     */
    public function recordPaymentEarning(
        Booking $booking,
        string $paymentStage,
        float $paymentAmount,
        ?string $transactionId = null
    ): ?MitraEarning {
        $booking->loadMissing('property');

        $mitraId = $booking->property?->mitra_id;

        if (
            ! $mitraId ||
            $paymentAmount <= 0
        ) {
            return null;
        }

        $existing = MitraEarning::query()
            ->where(
                'booking_id',
                $booking->id
            )
            ->where(
                'payment_stage',
                $paymentStage
            )
            ->where(
                'type',
                'payment'
            )
            ->first();

        if ($existing) {
            return $existing;
        }

        $totalPrice = (float)
            $booking->total_price;

        $totalMitraPayout = (float) (
            $booking->mitra_payout_amount ?? 0
        );

        if (
            $totalPrice <= 0 ||
            $totalMitraPayout <= 0
        ) {
            return null;
        }

        $alreadyRecorded = (float)
            MitraEarning::query()
                ->where(
                    'booking_id',
                    $booking->id
                )
                ->where(
                    'type',
                    'payment'
                )
                ->sum('amount');

        if ($paymentStage === 'dp') {

            $amount = round(
                $totalMitraPayout *
                (
                    $paymentAmount /
                    $totalPrice
                ),
                2
            );

        } else {

            $amount = round(
                $totalMitraPayout -
                $alreadyRecorded,
                2
            );
        }

        $amount = min(
            max(
                $amount,
                0
            ),
            max(
                $totalMitraPayout -
                $alreadyRecorded,
                0
            )
        );

        if ($amount <= 0) {
            return null;
        }

        return MitraEarning::create([
            'mitra_id' =>
                $mitraId,

            'booking_id' =>
                $booking->id,

            'property_id' =>
                $booking->property_id,

            'payment_stage' =>
                $paymentStage,

            'type' =>
                'payment',

            'amount' =>
                $amount,

            'transaction_id' =>
                $transactionId,

            'description' =>
                $paymentStage === 'dp'
                    ? 'Hak pendapatan Mitra dari pembayaran DP booking.'
                    : 'Hak pendapatan Mitra dari pembayaran pelunasan booking.',
        ]);
    }

    /**
     * Membuat koreksi negatif ketika pembayaran booking direfund.
     */
    public function reverseBookingEarnings(
        Booking $booking,
        string $reason =
            'Koreksi karena refund booking.'
    ): ?MitraEarning {

        $booking->loadMissing('property');

        $currentLedger = (float)
            MitraEarning::query()
                ->where(
                    'booking_id',
                    $booking->id
                )
                ->sum('amount');

        if ($currentLedger <= 0) {
            return null;
        }

        return MitraEarning::create([
            'mitra_id' =>
                $booking->property->mitra_id,

            'booking_id' =>
                $booking->id,

            'property_id' =>
                $booking->property_id,

            'payment_stage' =>
                null,

            'type' =>
                'refund_reversal',

            'amount' =>
                -$currentLedger,

            'transaction_id' =>
                $booking->refund_transaction_id,

            'description' =>
                $reason,

            'reversed_at' =>
                now(),
        ]);
    }

    public function ledgerBalance(
        int $mitraId
    ): float {
        return round(
            (float)
            MitraEarning::query()
                ->where(
                    'mitra_id',
                    $mitraId
                )
                ->sum('amount'),
            2
        );
    }

    public function totalWithdrawnOrReserved(
        int $mitraId
    ): float {
        return round(
            (float)
            \App\Models\MitraWithdrawal::query()
                ->where(
                    'mitra_id',
                    $mitraId
                )
                ->whereIn(
                    'status',
                    [
                        'pending',
                        'approved',
                        'paid',
                    ]
                )
                ->sum('amount'),
            2
        );
    }

    public function availableForWithdrawal(
        int $mitraId
    ): float {
        return max(
            0,
            round(
                $this->ledgerBalance($mitraId)
                -
                $this->totalWithdrawnOrReserved(
                    $mitraId
                ),
                2
            )
        );
    }
}