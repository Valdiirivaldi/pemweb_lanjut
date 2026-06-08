<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Course extends Model
{
    protected $fillable = [
        'title',
        'description',
        'tentor_id',
    ];

    // -------------------------------------------------------
    // Relationships
    // -------------------------------------------------------

    /**
     * Tentor yang memiliki/mengampu kelas ini.
     * Course → belongsTo User (sebagai Tentor)
     */
    public function tentor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tentor_id');
    }

    /**
     * Semua modul/bab yang ada di dalam kelas ini.
     * Course → hasMany Module
     */
    public function modules(): HasMany
    {
        return $this->hasMany(Module::class);
    }

    /**
     * Semua kuis yang ada di dalam kelas ini.
     * Course → hasMany Quiz
     */
    public function quizzes(): HasMany
    {
        return $this->hasMany(Quiz::class);
    }

    /**
     * Semua siswa yang terdaftar (di-enroll) di kelas ini.
     * Course → belongsToMany User (sebagai Siswa) via course_user
     */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'course_user')
            ->withPivot(['is_unlocked', 'status', 'unlocked_at', 'unlocked_by'])
            ->withTimestamps();
    }

    /**
     * Semua percobaan kuis dari seluruh siswa di kelas ini.
     * Course → hasManyThrough QuizAttempt via Quiz
     * Berguna untuk Tentor melihat rekap nilai seluruh siswa di kelasnya.
     */
    public function quizAttempts(): HasManyThrough
    {
        return $this->hasManyThrough(QuizAttempt::class, Quiz::class);
    }
}
