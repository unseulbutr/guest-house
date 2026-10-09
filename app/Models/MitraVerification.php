<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MitraVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'identity_type',
        'identity_number',
        'identity_photo',
        'selfie_photo',
        'status',
        'rejection_reason',
        'terms_accepted_at',
        'submitted_at',
        'verified_at',
        'verified_by',
    ];

    protected function casts(): array
    {
        return [
            'terms_accepted_at' => 'datetime',
            'submitted_at' => 'datetime',
            'verified_at' => 'datetime',
        ];
    }

    /**
     * User yang mengajukan menjadi Mitra.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Admin / Super Admin yang melakukan verifikasi.
     */
    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}