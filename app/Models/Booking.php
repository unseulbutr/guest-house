<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_code', 'customer_id', 'property_id', 'check_in', 'check_out',
        'guest_count', 'subtotal', 'commission_percentage', 'commission_amount',
        'mitra_payout_amount', 'total_price', 'payment_method',
        'qris_transaction_id', 'payment_status', 'status',
    ];

    protected function casts(): array
    {
        return [
            'check_in' => 'date',
            'check_out' => 'date',
        ];
    }

    protected static function booted()
    {
        static::creating(function (Booking $booking) {
            if (empty($booking->booking_code)) {
                $booking->booking_code = 'BK-' . strtoupper(Str::random(8));
            }
        });
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * True kalau booking ini sudah dibayar customer (payment_status = 'paid')
     * tapi masih menunggu Mitra klik Konfirmasi/Tolak (status masih 'pending' —
     * berlaku untuk skema pengelolaan "mandiri", lihat resolveStatusAfterPayment()
     * di BookingController). Dipakai buat highlight di dashboard Mitra.
     */
    public function needsMitraResponse(): bool
    {
        return $this->status === 'pending' && $this->payment_status === 'paid';
    }

    /**
     * Hitung subtotal, komisi (sudah termasuk pajak), dan payout mitra
     * berdasarkan properti + tanggal check-in/check-out.
     */
    public static function calculatePricing(Property $property, string $checkIn, string $checkOut): array
    {
        $nights = (new \DateTime($checkIn))->diff(new \DateTime($checkOut))->days;
        $nights = max($nights, 1);

        $subtotal = $property->price_per_night * $nights;
        $commissionAmount = round($subtotal * ($property->commission_percentage / 100), 2);
        $mitraPayout = $subtotal - $commissionAmount;

        return [
            'nights' => $nights,
            'subtotal' => $subtotal,
            'commission_percentage' => $property->commission_percentage,
            'commission_amount' => $commissionAmount,
            'mitra_payout_amount' => $mitraPayout,
            'total_price' => $subtotal, // customer bayar sebesar subtotal
        ];
    }
}