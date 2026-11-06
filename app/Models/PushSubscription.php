<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PushSubscription extends Model
{
    protected $fillable = [
        'user_id',
        'endpoint',
        'endpoint_hash',
        'public_key',
        'auth_token',
        'content_encoding',
        'user_agent',
    ];

    /**
     * Kunci kriptografi langganan tidak pernah ikut diserialisasi ke JSON/array.
     */
    protected $hidden = [
        'endpoint',
        'public_key',
        'auth_token',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function hashEndpoint(string $endpoint): string
    {
        return hash('sha256', $endpoint);
    }
}

