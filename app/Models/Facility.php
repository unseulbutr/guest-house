<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'icon'];

    public function properties()
    {
        return $this->belongsToMany(Property::class, 'property_facility')
            ->withPivot('is_available')
            ->withTimestamps();
    }
}
