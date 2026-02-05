<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('photobooth_baju_clicks', function (Blueprint $table) {
            $table->id();

            // FK ke photobooth_datasets (UUID)
            $table->uuid('photobooth_dataset_id');

            $table->string('gender_mode');
            // pria, wanita, pria_wanita, dll

            $table->unsignedTinyInteger('people_count')->default(1);

            $table->timestamp('clicked_at')->useCurrent();

            // Index
            $table->index(['photobooth_dataset_id', 'gender_mode']);

            // Foreign key manual (WAJIB untuk UUID)
            $table->foreign('photobooth_dataset_id')
                ->references('id')
                ->on('photobooth_datasets')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('photobooth_baju_clicks');
    }
};
