<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name', 'email', 'password', 'phone', 'avatar', 'is_active',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // Relasi: properti milik mitra ini
    public function properties()
    {
        return $this->hasMany(Property::class, 'mitra_id');
    }

    // Relasi: booking yang dibuat customer ini
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'customer_id');
    }

    // Relasi: artikel yang ditulis admin ini
    public function articles()
    {
        return $this->hasMany(Article::class, 'author_id');
    }

    // Relasi: properti yang di-save/wishlist customer ini
    public function savedProperties()
    {
        return $this->belongsToMany(Property::class, 'saved_properties', 'customer_id', 'property_id')
            ->withTimestamps();
    }
}
