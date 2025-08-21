<?php

namespace App\Http\Controllers;

use App\Models\Quizzes;
use App\Models\Attempts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\SubmitAttemptRequest; // Import the custom form request

class QuizAttemptController extends Controller
{
    /**
     * Start a new quiz attempt
     */
    public function start(Request $request, Quizzes $quiz)
    {
        // Check if user already has an active attempt
        $activeAttempt = Attempts::where('user_id', Auth::id())
            ->where('quiz_id', $quiz->id)
            ->whereNull('completed_at')
            ->first();

        if ($activeAttempt) {
            return redirect()->route('quizzes.attempts.show', [$quiz, $activeAttempt]);
        }

        // Create new attempt
        $attempt = Attempts::create([
            'user_id' => Auth::id(),
            'quiz_id' => $quiz->id,
            'started_at' => now(),
            'answers' => []
        ]);

        return redirect()->route('quizzes.attempts.show', [$quiz, $attempt]);
    }

    /**
     * Show quiz attempt page
     */
    public function show(Quizzes $quiz, Attempts $attempt)
    {
        // Authorization check
        if ($attempt->user_id !== Auth::id()) {
            abort(403);
        }

        // Redirect if already completed
        if ($attempt->completed_at) {
            return redirect()->route('quizzes.results', [$quiz, $attempt]);
        }

        // Load quiz with questions and options
        $quiz->load(['questions' => function ($query) {
            $query->with(['options' => function ($query) {
                $query->orderBy('id');
            }]);
        }]);

        // Calculate time left (in seconds)
        $timeLeft = ($quiz->duration_minutes * 60) - $attempt->started_at->diffInSeconds();

        return view('quizzes.attempt', [
            'quiz' => $quiz,
            'attempt' => $attempt,
            'timeLeft' => max(0, $timeLeft) // Ensure not negative
        ]);
    }

    /**
     * Submit quiz attempt
     */
    public function submit(SubmitAttemptRequest $request, Quizzes $quiz, Attempts $attempt)
    {
        // Authorization check
        if ($attempt->user_id !== Auth::id() || $attempt->completed_at) {
            abort(403);
        }

        // The request is already validated by SubmitAttemptRequest
        $validated = $request->validated();

        // Calculate score
        $score = $this->calculateScore($quiz, $validated['answers']);

        // Update attempt
        $attempt->update([
            'answers' => $validated['answers'],
            'score' => $score,
            'completed_at' => now()
        ]);

        return redirect()->route('quizzes.results', [$quiz, $attempt])
            ->with('success', 'Quiz submitted successfully!');
    }

    /**
     * Calculate quiz score
     */
    protected function calculateScore(Quizzes $quiz, array $answers): int
    {
        $totalMarks = 0;
        $quiz->load('questions.options');

        foreach ($quiz->questions as $question) {
            $userAnswer = collect($answers)->firstWhere('question_id', $question->id);

            if (!$userAnswer) {
                continue;
            }

            if ($question->type === 'text') {
                // Text answers need manual grading
                continue;
            }

            $selectedOptions = $userAnswer['selected'] ?? null;
            // Normalize selectedOptions to an array for consistent processing
            if ($question->type === 'single') {
                $selectedOptions = is_numeric($selectedOptions) ? [$selectedOptions] : [];
            } elseif ($question->type === 'multiple') {
                $selectedOptions = is_array($selectedOptions) ? $selectedOptions : [];
            } else {
                $selectedOptions = []; // Default for other types or if not set
            }
            
            $correctOptions = $question->options
                ->where('is_correct', true)
                ->pluck('id')
                ->toArray();

            // Check if answer is correct
            if ($question->type === 'single') {
                // For single choice, selectedOptions should contain exactly one element
                $isCorrect = count($selectedOptions) === 1 && 
                             in_array($selectedOptions[0], $correctOptions);
            } else { // multiple
                // For multiple choice, selectedOptions must exactly match correctOptions
                $isCorrect = empty(array_diff($selectedOptions, $correctOptions)) && 
                             empty(array_diff($correctOptions, $selectedOptions));
            }

            if ($isCorrect) {
                $totalMarks += $question->marks;
            }
        }

        // Calculate percentage
        $totalPossible = $quiz->questions->sum('marks');
        return $totalPossible > 0 ? round(($totalMarks / $totalPossible) * 100) : 0;
    }
}
