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
        'subtotal',
        'commission_percentage',
        'commission_amount',
        'mitra_payout_amount',
        'total_price',
        'payment_method',
        'qris_transaction_id',
        'payment_status',
        'refund_status',
        'refund_amount',
        'refund_transaction_id',
        'refunded_at',
        'refund_reason',
        'rejected_by_mitra',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'check_in' => 'date',
            'check_out' => 'date',
            'refunded_at' => 'datetime',
            'refund_amount' => 'decimal:2',
            'rejected_by_mitra' => 'boolean',
        ];
    }

    protected static function booted()
    {
        static::creating(function (Booking $booking) {

            if (empty($booking->booking_code)) {
                $booking->booking_code =
                    'BK-' . strtoupper(Str::random(8));
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
        return $this->belongsTo(User::class, 'customer_id');
    }

    /*
    |--------------------------------------------------------------------------
    | PROPERTY
    |--------------------------------------------------------------------------
    */

    public function property()
    {
        return $this->belongsTo(Property::class);
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
        return $this->hasOne(PropertyReview::class);
    }

    /*
    |--------------------------------------------------------------------------
    | EXTENSIONS
    |--------------------------------------------------------------------------
    */

    public function extensions()
    {
        return $this->hasMany(BookingExtension::class)
            ->latest();
    }

    /*
    |--------------------------------------------------------------------------
    | BOOKING STATUS
    |--------------------------------------------------------------------------
    */

    public function needsMitraResponse(): bool
    {
        return $this->status === 'pending'
            && $this->payment_status === 'paid';
    }

    public function isRefundPending(): bool
    {
        return $this->refund_status === 'pending';
    }

    public function isRefunded(): bool
    {
        return $this->payment_status === 'refunded'
            && $this->refund_status === 'completed';
    }

    /*
    |--------------------------------------------------------------------------
    | REVIEW ELIGIBILITY
    |--------------------------------------------------------------------------
    |
    | Customer dapat memberikan review apabila:
    | - pembayaran sudah paid
    | - tanggal check-out sudah lewat
    | - belum pernah memberikan review
    |
    */

    public function canBeReviewed(): bool
    {
        if ($this->payment_status !== 'paid') {
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

        $nights = (new \DateTime($checkIn))
            ->diff(new \DateTime($checkOut))
            ->days;

        $nights = max($nights, 1);

        $subtotal =
            $property->price_per_night * $nights;

        $commissionAmount = round(
            $subtotal *
            ($property->commission_percentage / 100),
            2
        );

        $mitraPayout =
            $subtotal - $commissionAmount;

        return [
            'nights' =>
                $nights,

            'subtotal' =>
                $subtotal,

            'commission_percentage' =>
                $property->commission_percentage,

            'commission_amount' =>
                $commissionAmount,

            'mitra_payout_amount' =>
                $mitraPayout,

            'total_price' =>
                $subtotal,
        ];
    }
}