<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MitraEarning extends Model
{
    use HasFactory;

    protected $fillable = [
        'mitra_id',
        'booking_id',
        'property_id',
        'payment_stage',
        'type',
        'amount',
        'transaction_id',
        'description',
        'reversed_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'reversed_at' => 'datetime',
        ];
    }

    public function mitra(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'mitra_id'
        );
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(
            Booking::class
        );
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(
            Property::class
        );
    }
}