<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->unique();
            $table->foreignId('customer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->date('check_in');
            $table->date('check_out');
            $table->unsignedInteger('guest_count')->default(1);

            $table->decimal('subtotal', 12, 2);           // harga sewa x jumlah malam
            $table->decimal('commission_percentage', 5, 2); // disalin dari properti saat booking dibuat
            $table->decimal('commission_amount', 12, 2);   // subtotal * commission_percentage (sudah termasuk pajak)
            $table->decimal('mitra_payout_amount', 12, 2); // subtotal - commission_amount
            $table->decimal('total_price', 12, 2);         // yang dibayar customer

            $table->string('payment_method')->default('qris');
            $table->string('qris_transaction_id')->nullable();
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
