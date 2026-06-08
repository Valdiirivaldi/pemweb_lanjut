<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tentor extends Model
{
    protected $fillable = ['user_id', 'unique_id'];

    /**
     * Akun user yang terkait dengan profil Tentor ini.
     * Tentor → belongsTo User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
