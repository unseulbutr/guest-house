<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table(
            'booking_extensions',
            function (Blueprint $table) {

                $table->string('payment_method')
                    ->nullable()
                    ->after('status');

                $table->string('payment_status')
                    ->default('pending')
                    ->after('payment_method');

                $table->string('qris_transaction_id')
                    ->nullable()
                    ->after('payment_status');

                $table->timestamp('payment_deadline')
                    ->nullable()
                    ->after('qris_transaction_id');

                $table->timestamp('paid_at')
                    ->nullable()
                    ->after('payment_deadline');
            }
        );
    }

    public function down(): void
    {
        Schema::table(
            'booking_extensions',
            function (Blueprint $table) {

                $table->dropColumn([
                    'payment_method',
                    'payment_status',
                    'qris_transaction_id',
                    'payment_deadline',
                    'paid_at',
                ]);
            }
        );
    }
};