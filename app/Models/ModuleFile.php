<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModuleFile extends Model
{
    protected $fillable = [
        'module_id',
        'file_name',
        'file_path',
        'file_size',
    ];

    /**
     * Modul yang memiliki file lampiran ini.
     * ModuleFile → belongsTo Module
     */
    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }
}
