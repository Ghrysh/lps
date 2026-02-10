<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('points', function (Blueprint $table) {
            $table->id();

            // PERBAIKAN: Gunakan foreignUuid karena tabel users menggunakan UUID
            $table->foreignUuid('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            $table->integer('nilai')->default(0);
            
            // Kolom ini wajib ada agar controller VisitorController tidak error
            // (Untuk menyimpan data seperti "POS 1", "POS 2", dll)
            $table->string('keterangan')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('points');
    }
};