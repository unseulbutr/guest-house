<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('platform_settings', function (Blueprint $table) {
            $table->decimal('cancellation_fee_h7', 5, 2)
                ->default(0);

            $table->decimal('cancellation_fee_h3', 5, 2)
                ->default(5);

            $table->decimal('cancellation_fee_h2', 5, 2)
                ->default(10);

            $table->decimal('cancellation_fee_h1', 5, 2)
                ->default(15);

            $table->decimal('cancellation_fee_same_day', 5, 2)
                ->default(20);
        });
    }

    public function down(): void
    {
        Schema::table('platform_settings', function (Blueprint $table) {
            $table->dropColumn([
                'cancellation_fee_h7',
                'cancellation_fee_h3',
                'cancellation_fee_h2',
                'cancellation_fee_h1',
                'cancellation_fee_same_day',
            ]);
        });
    }
};