<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | PEMBAYARAN DP
            |--------------------------------------------------------------------------
            */

            $table->decimal('dp_amount', 15, 2)
                ->nullable()
                ->after('total_price');

            $table->timestamp('dp_paid_at')
                ->nullable()
                ->after('dp_amount');


            /*
            |--------------------------------------------------------------------------
            | PELUNASAN
            |--------------------------------------------------------------------------
            */

            $table->decimal('remaining_amount', 15, 2)
                ->nullable()
                ->after('dp_paid_at');

            $table->timestamp('settlement_deadline')
                ->nullable()
                ->after('remaining_amount');

            $table->timestamp('settlement_paid_at')
                ->nullable()
                ->after('settlement_deadline');


            /*
            |--------------------------------------------------------------------------
            | PERSETUJUAN PERSYARATAN BOOKING
            |--------------------------------------------------------------------------
            */

            $table->timestamp('terms_accepted_at')
                ->nullable()
                ->after('settlement_paid_at');


            /*
            |--------------------------------------------------------------------------
            | BIAYA PEMBATALAN
            |--------------------------------------------------------------------------
            |
            | Contoh:
            | DP Rp250.000
            | Potongan 20% = Rp50.000
            |
            */

            $table->decimal('cancellation_fee_amount', 15, 2)
                ->nullable()
                ->after('refund_amount');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {

            $table->dropColumn([
                'dp_amount',
                'dp_paid_at',
                'remaining_amount',
                'settlement_deadline',
                'settlement_paid_at',
                'terms_accepted_at',
                'cancellation_fee_amount',
            ]);

        });
    }
};