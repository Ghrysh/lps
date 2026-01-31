<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Gallery extends Model
{
    use HasFactory;

    /**
     * Menonaktifkan auto-increment karena menggunakan UUID.
     */
    public $incrementing = false;

    /**
     * Menentukan tipe data primary key adalah string.
     */
    protected $keyType = 'string';

    /**
     * Kolom yang boleh diisi secara massal.
     */
    protected $fillable = [
        'id',
        'title',
        'description',
        'image_path',
    ];

    /**
     * Boot function untuk otomatis mengisi UUID saat data dibuat.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    /**
     * Accessor untuk mendapatkan URL penuh dari gambar.
     * Contoh penggunaan: $gallery->image_url
     */
    public function getImageUrlAttribute()
    {
        // Mengarah langsung ke public/assets/gallery/nama_file.jpg
        return asset('assets/gallery/' . $this->image_path);
    }
}