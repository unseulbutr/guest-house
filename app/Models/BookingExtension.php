<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingExtension extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'old_check_out',
        'new_check_out',
        'additional_nights',
        'additional_amount',
        'status',
        'customer_note',
        'rejection_reason',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'old_check_out' => 'date',
            'new_check_out' => 'date',
            'additional_amount' => 'decimal:2',
            'approved_at' => 'datetime',
        ];
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
}