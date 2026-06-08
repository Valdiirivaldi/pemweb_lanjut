<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModuleSubmission extends Model
{
    protected $fillable = [
        'module_id',
        'siswa_id',
        'file_path',
        'file_name',
        'file_size',
        'link_url',
        'notes',
        'submitted_at',
    ];

    /**
     * Mengubah tipe data kolom menjadi tipe PHP yang sesuai.
     * submitted_at dikonversi menjadi objek DateTime.
     */
    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
        ];
    }

    /**
     * Modul tempat tugas ini dikumpulkan.
     * ModuleSubmission → belongsTo Module
     */
    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    /**
     * Siswa yang mengumpulkan tugas ini.
     * ModuleSubmission → belongsTo User (sebagai Siswa)
     */
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }
}
