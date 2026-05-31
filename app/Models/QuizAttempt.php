<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuizAttempt extends Model
{
    protected $fillable = [
        'siswa_id',
        'quiz_id',
        'score',
        'certificate_path',
        'finished_at',
    ];

    protected $casts = [
        'finished_at' => 'datetime',
    ];

    // -------------------------------------------------------
    // Relationships
    // -------------------------------------------------------

    /**
     * Siswa yang mengerjakan kuis ini.
     * QuizAttempt → belongsTo User (sebagai Siswa)
     * Menggunakan foreign key 'siswa_id' (non-konvensional, wajib dideklarasikan).
     */
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }

    /**
     * Kuis yang dikerjakan pada attempt ini.
     * QuizAttempt → belongsTo Quiz
     */
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(QuizAttemptAnswer::class);
    }
}
