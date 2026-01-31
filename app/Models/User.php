<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids; // Tambahkan ini
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasUuids; // Gunakan di sini

    protected $fillable = ['name', 'email', 'password'];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}