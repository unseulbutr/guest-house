<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'platform_settings';

    protected $fillable = [
        'site_name',
        'contact_email',
        'contact_phone',
        'whatsapp_number',
        'default_commission_mandiri',
        'default_commission_dikelola',
        'maintenance_mode',
        'cancellation_fee_h7',
        'cancellation_fee_h3',
        'cancellation_fee_h2',
        'cancellation_fee_h1',
        'cancellation_fee_same_day',
    ];

    protected function casts(): array
    {
        return [
            'maintenance_mode' => 'boolean',
            'default_commission_mandiri' => 'decimal:2',
            'default_commission_dikelola' => 'decimal:2',
            'cancellation_fee_h7' => 'decimal:2',
'cancellation_fee_h3' => 'decimal:2',
'cancellation_fee_h2' => 'decimal:2',
'cancellation_fee_h1' => 'decimal:2',
'cancellation_fee_same_day' => 'decimal:2',
        ];
    }

    /**
     * Pengaturan platform cuma ADA SATU BARIS (singleton). Dipanggil dari mana
     * pun butuh setting (SettingController, atau nanti saat hitung komisi
     * default booking baru) — otomatis bikin baris pertama kalau belum ada,
     * jadi tidak akan pernah null.
     */
    public static function current(): self
    {
        return static::firstOrCreate([], [
            'site_name' => 'Guest House',
            'default_commission_mandiri' => 15,
            'default_commission_dikelola' => 45,
            'maintenance_mode' => false,
        ]);
    }
}