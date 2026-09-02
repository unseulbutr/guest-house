<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Kolom `type` sebelumnya kemungkinan enum(['guesthouse','kost_harian']) yang
     * membatasi nilai di level database. Diganti ke string biasa supaya nambah
     * tipe baru (villa, apartemen, dst) ke depannya cukup lewat validasi di
     * PropertyController, tidak perlu migration setiap kali.
     */
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->string('type', 50)->default('guesthouse')->change();
        });
    }

    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->enum('type', ['guesthouse', 'kost_harian'])->default('guesthouse')->change();
        });
    }
};