<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mitra_earnings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('mitra_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('booking_id')
                ->constrained('bookings')
                ->cascadeOnDelete();

            $table->foreignId('property_id')
                ->constrained('properties')
                ->cascadeOnDelete();

            $table->string('payment_stage', 30)->nullable();

            $table->string('type', 30)
                ->default('payment');

            $table->decimal('amount', 15, 2);

            $table->string('transaction_id')
                ->nullable();

            $table->string('description')
                ->nullable();

            $table->timestamp('reversed_at')
                ->nullable();

            $table->timestamps();

            $table->unique(
                [
                    'booking_id',
                    'payment_stage',
                    'type'
                ],
                'mitra_earnings_booking_stage_type_unique'
            );

            $table->index([
                'mitra_id',
                'created_at'
            ]);

            $table->index([
                'booking_id',
                'type'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mitra_earnings');
    }
};