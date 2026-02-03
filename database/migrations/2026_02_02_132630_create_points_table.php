<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('points', function (Blueprint $blueprint) {
            $blueprint->id();
            // Menggunakan foreignUuid karena relasinya berasal dari UUID
            $blueprint->foreignUuid('user_id')
                      ->constrained('users')
                      ->onDelete('cascade'); 
            
            // Kolom nilai (bisa integer atau decimal tergantung kebutuhan)
            $blueprint->integer('nilai')->default(0);
            
            $blueprint->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('points');
    }
};