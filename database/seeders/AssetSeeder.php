<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gallery;
use Illuminate\Support\Str;

class AssetSeeder extends Seeder
{
    public function run(): void
    {
        // Data 1: Mouse
        Gallery::create([
            'id' => Str::uuid(),
            'title' => 'Mouse Kantor Logitech',
            'description' => 'Mouse optik kabel warna hitam, aset ruang kerja staf LPS.',
            'image_path' => 'mouse.webp',
        ]);

        // Data 2: Kipas Angin
        Gallery::create([
            'id' => Str::uuid(),
            'title' => 'Kipas Angin Meja',
            'description' => 'Kipas angin kecil merk Sekai, aset inventaris ruang server.',
            'image_path' => 'kipas.jpg',
        ]);
    }
}