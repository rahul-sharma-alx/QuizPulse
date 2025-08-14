<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Attempts extends Model
{
    protected $fillable = [
        'user_id',
        'quiz_id',
        'answers',
        'score',
        'started_at',
        'completed_at'
    ];

    protected $casts = [
        'answers' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'score' => 'integer'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quizzes::class);
    }

    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->completed_at 
                ? 'completed' 
                : ($this->started_at ? 'in_progress' : 'not_started')
        );
    }

    public function calculateScore(): int
    {
        if (!$this->quiz || !$this->answers) {
            return 0;
        }

        $totalMarks = 0;
        $questions = $this->quiz->questions()->with('options')->get();

        foreach ($questions as $question) {
            $userAnswer = collect($this->answers)
                ->firstWhere('question_id', $question->id);

            if (!$userAnswer) continue;

            if ($question->type === 'text') {
                // Text answers need manual grading
                continue;
            }

            $selectedOptions = $userAnswer['selected'] ?? [];
            $correctOptions = $question->options
                ->where('is_correct', true)
                ->pluck('id')
                ->toArray();

            // For single/multiple choice questions
            if ($question->type === 'single') {
                $isCorrect = count($selectedOptions) === 1 && 
                             in_array($selectedOptions[0], $correctOptions);
            } else { // multiple
                $isCorrect = empty(array_diff($selectedOptions, $correctOptions)) && 
                             empty(array_diff($correctOptions, $selectedOptions));
            }

            if ($isCorrect) {
                $totalMarks += $question->marks;
            }
        }

        return $totalMarks;
    }

    public function getQuestionAnswer($questionId)
    {
        return collect($this->answers)
            ->firstWhere('question_id', $questionId);
    }
}