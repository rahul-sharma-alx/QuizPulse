<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Questions extends Model
{
    protected $fillable = [
        'quiz_id',
        'text',
        'type',
        'marks'
    ];

    protected $casts = [
        'marks' => 'integer'
    ];

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quizzes::class, 'quiz_id');
    }

    public function options(): HasMany
    {
        return $this->hasMany(Options::class, 'question_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Answers::class);
    }

    public function getTypeNameAttribute(): string
    {
        return match($this->type) {
            'single' => 'Single Choice',
            'multiple' => 'Multiple Choice',
            'text' => 'Text Answer',
            default => 'Unknown'
        };
    }
}
