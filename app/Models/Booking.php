<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\PropertyReview;
use App\Models\BookingExtension;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_code',

        'customer_id',
        'property_id',

        'check_in',
        'check_out',
        'guest_count',

        /*
        |--------------------------------------------------------------------------
        | DATA TAMU
        |--------------------------------------------------------------------------
        */
        'guest_name',
        'guest_phone',
        'guest_email',
        'guest_address',

        'subtotal',

        'commission_percentage',
        'commission_amount',
        'mitra_payout_amount',

        'total_price',

        /*
        |--------------------------------------------------------------------------
        | PEMBAYARAN
        |--------------------------------------------------------------------------
        */

        'payment_method',
        'qris_transaction_id',

        'payment_status',

        /*
        |--------------------------------------------------------------------------
        | DP 50%
        |--------------------------------------------------------------------------
        */

        'dp_amount',
        'dp_paid_at',

        /*
        |--------------------------------------------------------------------------
        | PELUNASAN
        |--------------------------------------------------------------------------
        */

        'remaining_amount',
        'settlement_deadline',
        'settlement_paid_at',

        /*
        |--------------------------------------------------------------------------
        | SYARAT BOOKING
        |--------------------------------------------------------------------------
        */

        'terms_accepted_at',

        /*
        |--------------------------------------------------------------------------
        | REFUND
        |--------------------------------------------------------------------------
        */

        'refund_status',
        'refund_amount',
        'cancellation_fee_amount',
        'refund_transaction_id',
        'refunded_at',
        'refund_reason',

        /*
        |--------------------------------------------------------------------------
        | MITRA
        |--------------------------------------------------------------------------
        */

        'rejected_by_mitra',

        /*
        |--------------------------------------------------------------------------
        | STATUS BOOKING
        |--------------------------------------------------------------------------
        */

        'status',
    ];

    protected function casts(): array
    {
        return [
            /*
            |--------------------------------------------------------------------------
            | TANGGAL BOOKING
            |--------------------------------------------------------------------------
            */

            'check_in' => 'date',
            'check_out' => 'date',

            /*
            |--------------------------------------------------------------------------
            | PEMBAYARAN
            |--------------------------------------------------------------------------
            */

            'dp_amount' => 'decimal:2',
            'remaining_amount' => 'decimal:2',

            /*
            |--------------------------------------------------------------------------
            | TIMESTAMP PEMBAYARAN
            |--------------------------------------------------------------------------
            */

            'dp_paid_at' => 'datetime',
            'settlement_deadline' => 'datetime',
            'settlement_paid_at' => 'datetime',

            /*
            |--------------------------------------------------------------------------
            | SYARAT
            |--------------------------------------------------------------------------
            */

            'terms_accepted_at' => 'datetime',

            /*
            |--------------------------------------------------------------------------
            | REFUND
            |--------------------------------------------------------------------------
            */

            'refunded_at' => 'datetime',
            'refund_amount' => 'decimal:2',
            'cancellation_fee_amount' => 'decimal:2',

            /*
            |--------------------------------------------------------------------------
            | BOOLEAN
            |--------------------------------------------------------------------------
            */

            'rejected_by_mitra' => 'boolean',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | BOOTED
    |--------------------------------------------------------------------------
    */

    protected static function booted()
    {
        static::creating(function (Booking $booking) {

            if (empty($booking->booking_code)) {
                $booking->booking_code =
                    'BK-' . strtoupper(
                        Str::random(8)
                    );
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | CUSTOMER
    |--------------------------------------------------------------------------
    */

    public function customer()
    {
        return $this->belongsTo(
            User::class,
            'customer_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | PROPERTY
    |--------------------------------------------------------------------------
    */

    public function property()
    {
        return $this->belongsTo(
            Property::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | REVIEW
    |--------------------------------------------------------------------------
    |
    | Satu booking hanya boleh mempunyai satu review.
    |
    */

    public function review()
    {
        return $this->hasOne(
            PropertyReview::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | EXTENSIONS
    |--------------------------------------------------------------------------
    */

    public function extensions()
    {
        return $this->hasMany(
            BookingExtension::class
        )->latest();
    }

    /*
    |--------------------------------------------------------------------------
    | PRICING
    |--------------------------------------------------------------------------
    */

    public static function calculatePricing(
        Property $property,
        string $checkIn,
        string $checkOut
    ): array {

        $checkInDate = \Carbon\Carbon::parse(
            $checkIn
        );

        $checkOutDate = \Carbon\Carbon::parse(
            $checkOut
        );

        /*
        |--------------------------------------------------------------------------
        | JUMLAH MALAM
        |--------------------------------------------------------------------------
        */

        $nights = $checkInDate->diffInDays(
            $checkOutDate
        );

        /*
        |--------------------------------------------------------------------------
        | HARGA PER MALAM
        |--------------------------------------------------------------------------
        */

        $pricePerNight = (float) (
            $property->price_per_night ?? 0
        );

        /*
        |--------------------------------------------------------------------------
        | SUBTOTAL
        |--------------------------------------------------------------------------
        */

        $subtotal = round(
            $pricePerNight * $nights,
            2
        );

        /*
        |--------------------------------------------------------------------------
        | KOMISI
        |--------------------------------------------------------------------------
        |
        | Mengikuti data komisi property yang sudah ada.
        |
        */

        $commissionPercentage = (float) (
            $property->commission_percentage ?? 0
        );

        $commissionAmount = round(
            $subtotal *
            ($commissionPercentage / 100),
            2
        );

        /*
        |--------------------------------------------------------------------------
        | MITRA PAYOUT
        |--------------------------------------------------------------------------
        */

        $mitraPayoutAmount = round(
            $subtotal - $commissionAmount,
            2
        );

        /*
        |--------------------------------------------------------------------------
        | TOTAL PRICE
        |--------------------------------------------------------------------------
        */

        $totalPrice = $subtotal;

        return [
            'nights' =>
                $nights,

            'price_per_night' =>
                $pricePerNight,

            'subtotal' =>
                $subtotal,

            'commission_percentage' =>
                $commissionPercentage,

            'commission_amount' =>
                $commissionAmount,

            'mitra_payout_amount' =>
                $mitraPayoutAmount,

            'total_price' =>
                $totalPrice,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | BOOKING STATUS
    |--------------------------------------------------------------------------
    */

    public function needsMitraResponse(): bool
    {
        return $this->status === 'pending'
            &&
            in_array(
                $this->payment_status,
                [
                    'dp_paid',
                    'paid',
                ],
                true
            );
    }

    /*
    |--------------------------------------------------------------------------
    | DP SUDAH DIBAYAR
    |--------------------------------------------------------------------------
    */

    public function isDpPaid(): bool
    {
        return $this->payment_status === 'dp_paid';
    }

    /*
    |--------------------------------------------------------------------------
    | SUDAH LUNAS
    |--------------------------------------------------------------------------
    */

    public function isFullyPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    /*
    |--------------------------------------------------------------------------
    | REFUND PENDING
    |--------------------------------------------------------------------------
    */

    public function isRefundPending(): bool
    {
        return $this->refund_status === 'pending';
    }

    /*
    |--------------------------------------------------------------------------
    | REFUNDED
    |--------------------------------------------------------------------------
    */

    public function isRefunded(): bool
    {
        return $this->payment_status === 'refunded'
            &&
            $this->refund_status === 'completed';
    }

    /*
    |--------------------------------------------------------------------------
    | PELUNASAN SUDAH DIBUKA
    |--------------------------------------------------------------------------
    */

    public function settlementIsOpen(): bool
    {
        if ($this->payment_status !== 'dp_paid') {
            return false;
        }

        if (!$this->check_in) {
            return false;
        }

        /*
        |--------------------------------------------------------------------------
        | Pelunasan dapat dilakukan kapan saja setelah DP,
        | sampai sebelum tanggal check-in.
        |--------------------------------------------------------------------------
        */
        return now()->lessThan(
            $this->check_in->copy()->startOfDay()
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DEADLINE PELUNASAN TERLEWAT
    |--------------------------------------------------------------------------
    */

    public function settlementDeadlinePassed(): bool
    {
        if (!$this->settlement_deadline) {
            return false;
        }

        return now()->greaterThan(
            $this->settlement_deadline
        );
    }

    /*
    |--------------------------------------------------------------------------
    | BISA DIREVIEW
    |--------------------------------------------------------------------------
    */

    public function canBeReviewed(): bool
    {
        if (
            !in_array(
                $this->payment_status,
                [
                    'paid',
                ],
                true
            )
        ) {
            return false;
        }

        if (!$this->check_out) {
            return false;
        }

        if ($this->check_out->isFuture()) {
            return false;
        }

        return !$this->review()->exists();
    }
}