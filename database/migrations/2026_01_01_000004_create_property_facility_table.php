<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('property_facility', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->foreignId('facility_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_available')->default(false); // true = centang, false = silang
            $table->timestamps();

            $table->unique(['property_id', 'facility_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property_facility');
    }
};
