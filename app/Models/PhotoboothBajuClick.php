<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhotoboothBajuClick extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'photobooth_dataset_id',
        'gender_mode',
        'people_count',
        'clicked_at',
    ];

    protected $casts = [
        'clicked_at' => 'datetime',
    ];

     public function dataset()
    {
        // Pastikan nama model dan primary key benar
        return $this->belongsTo(PhotoboothDataset::class, 'photobooth_dataset_id', 'id');
    }
}
