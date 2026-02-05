<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PhotoboothDataset extends Model
{
    protected $table = 'photobooth_datasets';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['title', 'image'];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (!$model->id) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    // Accessor image URL
    public function getImageUrlAttribute()
    {
        return asset('assets/baju/' . $this->image);
    }
}
