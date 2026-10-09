<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mitra_verifications', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Jenis identitas: KTP / SIM / Paspor
            $table->string('identity_type');

            // Nomor identitas
            $table->string('identity_number');

            // File foto identitas
            $table->string('identity_photo');

            // File selfie
            $table->string('selfie_photo');

            // pending / approved / rejected
            $table->string('status')->default('pending');

            // Alasan jika ditolak
            $table->text('rejection_reason')->nullable();

            // Persetujuan syarat
            $table->timestamp('terms_accepted_at')->nullable();

            // Waktu pengajuan
            $table->timestamp('submitted_at')->nullable();

            // Waktu diverifikasi
            $table->timestamp('verified_at')->nullable();

            // Admin/Super Admin yang melakukan verifikasi
            $table->foreignId('verified_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            // Satu user memiliki satu data pengajuan Mitra
            $table->unique('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mitra_verifications');
    }
};