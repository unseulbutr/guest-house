<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('property_reviews', function (Blueprint $table) {
            $table->id();

            $table->foreignId('property_id')
                ->constrained('properties')
                ->cascadeOnDelete();

            $table->foreignId('customer_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('booking_id')
                ->unique()
                ->constrained('bookings')
                ->cascadeOnDelete();

            $table->unsignedTinyInteger('score');

            $table->text('comment')->nullable();

            $table->timestamps();

            $table->index([
                'property_id',
                'score',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property_reviews');
    }
};