<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids; // Penting untuk UUID
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['name', 'slug'];

    // Relasi ke User
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}