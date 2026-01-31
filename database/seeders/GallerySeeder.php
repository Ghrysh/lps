<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gallery;
use Illuminate\Support\Str;

class GallerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Gallery::create([
            'id' => Str::uuid(),
            'title' => 'Korek Gas Tokai',
            'description' => 'Aset operasional standar LPS. Korek api gas tahan lama dengan mekanisme pemantik roda.',
            'image_path' => 'korek_tokai.jpg', // Pastikan file ini ada di public/assets/gallery
        ]);
    }
}