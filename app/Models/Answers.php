<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Answers extends Model
{
    protected $fillable = [
        'attempt_id',
        'question_id',
        'option_id',
        'text_answer',
        'is_correct',
        'marks_obtained'
    ];

    protected $casts = [
        'is_correct' => 'boolean'
    ];

    public function attempt(): BelongsTo
    {
        return $this->belongsTo(Attempts::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Questions::class);
    }

    public function option(): BelongsTo
    {
        return $this->belongsTo(Options::class);
    }

    public function getAnswerTextAttribute(): string
    {
        return $this->question->type === 'text' 
            ? $this->text_answer 
            : ($this->option ? $this->option->text : 'No answer');
    }
}