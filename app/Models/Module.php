<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    // -------------------------------------------------------
    // Relationships
    // -------------------------------------------------------

    /**
     * Kelas yang memiliki modul ini.
     * Module → belongsTo Course
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
