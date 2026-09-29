<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Buat kolom sementara tanpa CHECK constraint lama
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('payment_status_new')
                ->default('pending')
                ->after('payment_method');
        });

        // Salin data payment_status lama
        DB::statement("
            UPDATE bookings
            SET payment_status_new = payment_status
        ");

        // Hapus kolom lama yang memiliki CHECK constraint
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('payment_status');
        });

        // Ganti nama kolom baru menjadi payment_status
        Schema::table('bookings', function (Blueprint $table) {
            $table->renameColumn('payment_status_new', 'payment_status');
        });
    }

    public function down(): void
    {
        // Kembalikan ke struktur lama
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('payment_status_old')
                ->default('pending')
                ->after('payment_method');
        });

        DB::statement("
            UPDATE bookings
            SET payment_status_old =
                CASE
                    WHEN payment_status = 'dp_paid' THEN 'paid'
                    WHEN payment_status = 'failed' THEN 'failed'
                    WHEN payment_status = 'refunded' THEN 'refunded'
                    ELSE 'pending'
                END
        ");

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('payment_status');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->renameColumn('payment_status_old', 'payment_status');
        });
    }
};