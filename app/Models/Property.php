<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'mitra_id', 'name', 'slug', 'description', 'type', 'address', 'city',
        'latitude', 'longitude', 'bedroom_count', 'guest_capacity',
        'price_per_night', 'management_type', 'commission_percentage',
        'cover_image', 'status',
    ];

    protected function casts(): array
    {
        return [
            'price_per_night' => 'decimal:2',
            'commission_percentage' => 'decimal:2',
        ];
    }

    protected static function booted()
    {
        // slug otomatis dari nama
        static::creating(function (Property $property) {
            if (empty($property->slug)) {
                $property->slug = Str::slug($property->name) . '-' . Str::random(5);
            }

            // Komisi otomatis sesuai skema pengelolaan (sudah termasuk pajak)
            $property->commission_percentage = $property->management_type === 'dikelola' ? 45.00 : 15.00;
        });

        static::updating(function (Property $property) {
            if ($property->isDirty('management_type')) {
                $property->commission_percentage = $property->management_type === 'dikelola' ? 45.00 : 15.00;
            }
        });
    }

    public function mitra()
    {
        return $this->belongsTo(User::class, 'mitra_id');
    }

    public function facilities()
    {
        return $this->belongsToMany(Facility::class, 'property_facility')
            ->withPivot('is_available')
            ->withTimestamps();
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function savedByCustomers()
    {
        return $this->belongsToMany(User::class, 'saved_properties', 'property_id', 'customer_id')
            ->withTimestamps();
    }
}
