<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QrSession extends Model
{
    protected $keyType = 'int';

    protected $fillable = [
        'token',
        'user_id',
        'status',
        'type',
        'screen_id',
        'expired_at',
    ];

    protected $casts = [
        'token' => 'string',
        'user_id' => 'string',
        'expired_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}

