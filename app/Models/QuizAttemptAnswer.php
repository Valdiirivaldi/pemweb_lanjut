<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizAttemptAnswer extends Model
{
    protected $fillable = [
        'quiz_attempt_id',
        'question_id',
        'given_answer',
        'is_correct',
    ];

    /**
     * Mengubah tipe data kolom menjadi tipe PHP yang sesuai.
     * given_answer dikonversi menjadi array (mendukung single/multiple jawaban).
     * is_correct dikonversi menjadi boolean.
     */
    protected function casts(): array
    {
        return [
            'given_answer' => 'array',
            'is_correct' => 'boolean',
        ];
    }

    /**
     * Percobaan kuis yang memiliki jawaban ini.
     * QuizAttemptAnswer → belongsTo QuizAttempt
     */
    public function quizAttempt(): BelongsTo
    {
        return $this->belongsTo(QuizAttempt::class);
    }

    /**
     * Soal yang dijawab pada jawaban ini.
     * QuizAttemptAnswer → belongsTo Question
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
