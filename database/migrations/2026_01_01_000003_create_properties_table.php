<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mitra_id')->constrained('users')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('type')->default('guesthouse'); // guesthouse / kost_harian
            $table->string('address');
            $table->string('city');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->unsignedInteger('bedroom_count')->default(1);
            $table->unsignedInteger('guest_capacity')->default(2);
            $table->decimal('price_per_night', 12, 2);
            $table->enum('management_type', ['mandiri', 'dikelola'])->default('mandiri');
            $table->decimal('commission_percentage', 5, 2)->default(15.00); // 15 = mandiri, 45 = dikelola
            $table->string('cover_image')->nullable();
            $table->enum('status', ['pending', 'active', 'inactive', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
