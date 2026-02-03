<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Point extends Model
{
    use HasFactory;

    // Nama tabel (opsional jika sudah sesuai standar plural Laravel)
    protected $table = 'points';

    // Kolom yang boleh diisi secara massal
    protected $fillable = [
        'user_id',
        'nilai',
    ];

    /**
     * Relasi ke model User
     * Mengaitkan user_id di tabel points ke id di tabel users
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}