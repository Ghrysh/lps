<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('questions', function (Blueprint $table) {
            // Default 20 detik per soal, ditaruh setelah kolom question_text
            $table->integer('duration')->default(20)->after('question_text');
        });
    }

    public function down()
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn('duration');
        });
    }
};