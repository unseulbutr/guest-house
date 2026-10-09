<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MitraWithdrawal extends Model
{
    use HasFactory;

    protected $fillable = [
        'mitra_id',
        'amount',
        'status',
        'payment_method',
        'payment_account',
        'note',
        'admin_note',
        'processed_by',
        'requested_at',
        'approved_at',
        'paid_at',
        'rejected_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'requested_at' => 'datetime',
            'approved_at' => 'datetime',
            'paid_at' => 'datetime',
            'rejected_at' => 'datetime',
        ];
    }

    public function mitra(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'mitra_id'
        );
    }

    public function processor(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'processed_by'
        );
    }
}