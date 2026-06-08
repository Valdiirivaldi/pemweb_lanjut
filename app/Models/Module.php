<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Module extends Model
{
    protected $fillable = [
        'course_id',
        'title',
        'content',
        'video_url',
        'link_url',
        'pdf_path',
    ];

    /**
     * Kelas yang memiliki modul ini.
     * Module → belongsTo Course
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Semua file lampiran yang diunggah pada modul ini.
     * Module → hasMany ModuleFile
     */
    public function files(): HasMany
    {
        return $this->hasMany(ModuleFile::class);
    }

    /**
     * Semua pengumpulan tugas dari siswa untuk modul ini.
     * Module → hasMany ModuleSubmission
     */
    public function submissions(): HasMany
    {
        return $this->hasMany(ModuleSubmission::class);
    }
}
