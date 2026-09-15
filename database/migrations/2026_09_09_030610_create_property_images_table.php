<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * `cover_image` di tabel properties TETAP dipakai (untuk thumbnail di
     * card listing homepage, dll — supaya tidak perlu ubah tempat lain).
     * Tabel ini nampung foto TAMBAHAN untuk galeri di halaman detail
     * properti, biar bisa banyak foto kayak Traveloka.
     */
    public function up(): void
    {
        Schema::create('property_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->string('path');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property_images');
    }
};