<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Question extends Model
{
    protected $fillable = [
        'quiz_id',
        'question_text',
        'type',
        'options',
        'correct_options',
    ];

    protected $casts = [
        'options' => 'array',
        'correct_options' => 'array',
    ];

    /**
     * Mengecek apakah tipe soal ini adalah pilihan ganda (single choice).
     * Hanya ada satu jawaban benar.
     *
     * @return bool  true jika tipe 'single'
     */
    public function isSingleChoice(): bool
    {
        return $this->type === 'single';
    }

    /**
     * Mengecek apakah tipe soal ini adalah pilihan ganda ganda (multiple choice).
     * Bisa ada lebih dari satu jawaban benar.
     *
     * @return bool  true jika tipe 'multiple'
     */
    public function isMultipleChoice(): bool
    {
        return $this->type === 'multiple';
    }

    /**
     * Mengecek apakah tipe soal ini adalah benar/salah (true/false).
     *
     * @return bool  true jika tipe 'true_false'
     */
    public function isTrueFalse(): bool
    {
        return $this->type === 'true_false';
    }

    /**
     * Mendapatkan daftar kunci opsi jawaban (A, B, C, D, dst.).
     * Berguna untuk validasi jawaban siswa saat pengerjaan kuis.
     *
     * @return array  Array berisi kunci opsi, contoh: ['A', 'B', 'C', 'D']
     */
    public function getOptionKeys(): array
    {
        return array_keys($this->options ?? []);
    }

    /**
     * Kuis yang memiliki soal ini.
     * Question → belongsTo Quiz
     */
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }
}
