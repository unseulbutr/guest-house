<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_name')->default('Guest House');
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->decimal('default_commission_mandiri', 5, 2)->default(15.00);
            $table->decimal('default_commission_dikelola', 5, 2)->default(45.00);
            $table->boolean('maintenance_mode')->default(false);
            $table->timestamps();
        });

        // Baris default (row tunggal, id selalu 1)
        \DB::table('settings')->insert([
            'site_name' => 'Guest House',
            'contact_email' => 'support@guesthouse.test',
            'contact_phone' => null,
            'whatsapp_number' => null,
            'default_commission_mandiri' => 15.00,
            'default_commission_dikelola' => 45.00,
            'maintenance_mode' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};