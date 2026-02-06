<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('qr_sessions', function (Blueprint $table) {
            $table->id();

            // Token unik yang ada di QR
            $table->uuid('token')->unique();

            // User yang login lewat QR (UUID)
            $table->uuid('user_id')->nullable();

            // Status alur QR
            $table->enum('status', [
                'waiting',     // QR tampil, belum discan
                'logged_in',   // User berhasil scan & register
                'playing',     // Video greeting sedang diputar
                'skipped',     // Video di-skip dari device lain
                'expired'      // QR kadaluarsa
            ])->default('waiting');

            // Opsional: identitas layar / kiosk
            $table->string('screen_id')->nullable();

            // Waktu kadaluarsa QR
            $table->timestamp('expired_at')->nullable();

            $table->timestamps();

            // Foreign key manual ke users.id (UUID)
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qr_sessions');
    }
};
