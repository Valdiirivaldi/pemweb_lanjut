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

    public function isSingleChoice(): bool
    {
        return $this->type === 'single';
    }

    public function isMultipleChoice(): bool
    {
        return $this->type === 'multiple';
    }

    public function isTrueFalse(): bool
    {
        return $this->type === 'true_false';
    }

    public function getOptionKeys(): array
    {
        return array_keys($this->options ?? []);
    }

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }
}
