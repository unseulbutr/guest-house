<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {

            $table->string('refund_status')
                ->default('none')
                ->after('payment_status');

            $table->decimal('refund_amount', 12, 2)
                ->nullable()
                ->after('refund_status');

            $table->string('refund_transaction_id')
                ->nullable()
                ->after('refund_amount');

            $table->timestamp('refunded_at')
                ->nullable()
                ->after('refund_transaction_id');

            $table->text('refund_reason')
                ->nullable()
                ->after('refunded_at');

            $table->boolean('rejected_by_mitra')
                ->default(false)
                ->after('refund_reason');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'refund_status',
                'refund_amount',
                'refund_transaction_id',
                'refunded_at',
                'refund_reason',
                'rejected_by_mitra',
            ]);
        });
    }
};