<?php

namespace App\Http\Controllers;

use App\Models\Attempts;
use App\Models\Quizzes;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    public function index()
    {
        return view('results.index');
    }

    public function show(Quizzes $quiz, Attempts $attempt)
    {
        // Ensure the attempt belongs to the quiz and the current user
        if ($attempt->quiz_id !== $quiz->id || $attempt->user_id !== auth()->id()) {
            abort(403); // Forbidden if not the owner
        }

        // Load necessary relationships (if needed)
        $quiz->load('questions'); // Assuming questions are needed for review

        return view('quizzes.results', [
            'quiz' => $quiz,
            'attempt' => $attempt,
            'success' => session('success'), // Flash message from submission
        ]);
    }
}
