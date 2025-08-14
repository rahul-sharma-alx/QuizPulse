<?php

namespace App\Http\Controllers;

use App\Models\Attempts;
use App\Models\Quizzes;
use App\Models\Questions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    /**
     * Display a listing of quizzes.
     */
    public function index()
    {
        $role = Auth::user()->role;
        $quizzes = Quizzes::withCount(['questions', 'attempts'])
            ->where('is_published', true)
            ->latest()
            ->paginate(10);

        return view('quizzes.index', compact('quizzes', 'role'));
    }

    /**
     * Show the form for creating a new quiz.
     */
    public function create()
    {
        return view('quizzes.create');
    }

    /**
     * Store a newly created quiz.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_minutes' => 'required|integer|min:1',
            'is_published' => 'nullable|string'
        ]);
        $validated['is_published'] = $request->is_published === 'on' ? 1 : 0;
        
        $quiz = Auth::user()->quizzes()->create($validated);

        return redirect()->route('quizzes.show', $quiz)
            ->with('success', 'Quiz created successfully!');
    }

    /**
     * Display the specified quiz.
     */
    public function show(Quizzes $quiz)
    {
        $role = Auth::user()->role;
        $quiz->load(['questions', 'attempts.user']);

        return view('quizzes.show', compact('quiz', 'role'));
    }

    /**
     * Show the form for editing the quiz.
     */
    public function edit(Quizzes $quiz)
    {
        $this->authorize('update', $quiz);

        return view('quizzes.edit', compact('quiz'));
    }

    /**
     * Update the specified quiz.
     */
    public function update(Request $request, Quizzes $quiz)
    {
        $this->authorize('update', $quiz);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_minutes' => 'required|integer|min:1',
            'is_published' => 'boolean'
        ]);

        $quiz->update($validated);

        return redirect()->route('quizzes.show', $quiz)
            ->with('success', 'Quiz updated successfully!');
    }

    /**
     * Remove the specified quiz.
     */
    public function destroy(Quizzes $quiz)
    {
        $this->authorize('delete', $quiz);

        $quiz->delete();

        return redirect()->route('quizzes.index')
            ->with('success', 'Quiz deleted successfully!');
    }

    /**
     * Toggle quiz publish status
     */
    public function togglePublish(Quizzes $quiz)
    {
        $this->authorize('update', $quiz);

        $quiz->update(['is_published' => !$quiz->is_published]);

        return back()->with(
            'success',
            $quiz->is_published ? 'Quiz published!' : 'Quiz unpublished!'
        );
    }

    // Add these methods to your existing QuizController

    public function start(Quizzes $quiz)
    {
        $attempt = $quiz->attempts()->create([
            'user_id' => auth()->id(),
            'started_at' => now()
        ]);

        return redirect()->route('quizzes.attempt', [$quiz, $attempt]);
    }

    public function attempt(Quizzes $quiz, Attempts $attempt)
    {
        if ($attempt->user_id !== auth()->id()) {
            abort(403);
        }

        if ($attempt->status === 'completed') {
            return redirect()->route('quizzes.results', [$quiz, $attempt]);
        }

        $quiz->load(['questions.options']);

        return view('quizzes.attempt', [
            'quiz' => $quiz,
            'attempt' => $attempt,
            'timeLeft' => $quiz->duration_minutes * 60 - $attempt->started_at->diffInSeconds()
        ]);
    }

    public function submit(Request $request, Quizzes $quiz, Attempts $attempt)
    {
        if ($attempt->user_id !== auth()->id() || $attempt->completed_at) {
            abort(403);
        }

        $validated = $request->validate([
            'answers' => 'required|array',
            'answers.*.question_id' => 'required|exists:questions,id',
            'answers.*.selected' => 'nullable|array',
            'answers.*.selected.*' => 'integer',
            'answers.*.text' => 'nullable|string|max:1000'
        ]);

        $attempt->update([
            'answers' => $validated['answers'],
            'score' => $attempt->calculateScore(),
            'completed_at' => now()
        ]);

        return redirect()->route('quizzes.results', [$quiz, $attempt])
            ->with('success', 'Quiz submitted successfully!');
    }

    public function results(Quizzes $quiz, Attempts $attempt)
    {
        if ($attempt->user_id !== auth()->id() && auth()->user()->id !== $quiz->admin_id) {
            abort(403);
        }

        $quiz->load(['questions.options']);

        return view('quizzes.results', [
            'quiz' => $quiz,
            'attempt' => $attempt,
            'questions' => $quiz->questions
        ]);
    }
}
