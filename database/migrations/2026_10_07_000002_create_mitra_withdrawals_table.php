<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mitra_withdrawals', function (Blueprint $table) {
            $table->id();

            $table->foreignId('mitra_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->decimal('amount', 15, 2);

            $table->string('status', 20)
                ->default('pending');

            $table->string('payment_method', 30)
                ->default('bank_transfer');

            $table->string('payment_account')
                ->nullable();

            $table->text('note')
                ->nullable();

            $table->text('admin_note')
                ->nullable();

            $table->foreignId('processed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('requested_at')
                ->nullable();

            $table->timestamp('approved_at')
                ->nullable();

            $table->timestamp('paid_at')
                ->nullable();

            $table->timestamp('rejected_at')
                ->nullable();

            $table->timestamps();

            $table->index([
                'mitra_id',
                'status'
            ]);

            $table->index([
                'status',
                'created_at'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mitra_withdrawals');
    }
};