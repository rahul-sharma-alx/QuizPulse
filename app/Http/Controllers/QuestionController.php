<?php

namespace App\Http\Controllers;

use App\Models\Questions;
use App\Models\Quizzes;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function create(Quizzes $quiz)
    {
        // $this->authorize('update', $quiz);
        
        return view('questions.create', [
            'quiz' => $quiz,
            'types' => [
                'single' => 'Single Choice',
                'multiple' => 'Multiple Choice',
                'text' => 'Text Answer'
            ]
        ]);
    }

    public function store(Request $request, Quizzes $quiz)
    {
        // $this->authorize('update', $quiz);

        $validated = $request->validate([
            'text' => 'required|string|max:1000',
            'type' => 'required|in:single,multiple,text',
            'marks' => 'required|integer|min:1',
            'options' => 'required_if:type,single,multiple|array|min:2',
            'options.*' => 'required|string|max:255',
            'correct_options' => 'required_if:type,single,multiple|array',
            'correct_options.*' => 'integer'
        ]);

        $question = $quiz->questions()->create([
            'text' => $validated['text'],
            'type' => $validated['type'],
            'marks' => $validated['marks']
        ]);

        if (in_array($question->type, ['single', 'multiple'])) {
            foreach ($validated['options'] as $index => $optionText) {
                $question->options()->create([
                    'text' => $optionText,
                    'is_correct' => in_array($index, $validated['correct_options'] ?? [])
                ]);
            }
        }

        return redirect()
            ->route('quizzes.show', $quiz)
            ->with('success', 'Question added successfully!');
    }

    public function edit(Quizzes $quiz, Questions $question)
    {
        // $this->authorize('update', $quiz);

        return view('questions.edit', [
            'quiz' => $quiz,
            'question' => $question->load('options'),
            'types' => [
                'single' => 'Single Choice',
                'multiple' => 'Multiple Choice',
                'text' => 'Text Answer'
            ]
        ]);
    }

    public function update(Request $request, Quizzes $quiz, Questions $question)
    {
        // $this->authorize('update', $quiz);

        $validated = $request->validate([
            'text' => 'required|string|max:1000',
            'type' => 'required|in:single,multiple,text',
            'marks' => 'required|integer|min:1',
            'options' => 'required_if:type,single,multiple|array|min:2',
            'options.*' => 'required|string|max:255',
            'correct_options' => 'required_if:type,single,multiple|array',
            'correct_options.*' => 'integer'
        ]);

        $question->update([
            'text' => $validated['text'],
            'type' => $validated['type'],
            'marks' => $validated['marks']
        ]);

        // Delete existing options if type changed
        if ($question->wasChanged('type')) {
            $question->options()->delete();
        }

        if (in_array($question->type, ['single', 'multiple'])) {
            $question->options()->delete(); // Clear existing options
            
            foreach ($validated['options'] as $index => $optionText) {
                $question->options()->create([
                    'text' => $optionText,
                    'is_correct' => in_array($index, $validated['correct_options'] ?? [])
                ]);
            }
        }

        return redirect()
            ->route('quizzes.show', $quiz)
            ->with('success', 'Question updated successfully!');
    }

    public function destroy(Quizzes $quiz, Questions $question)
    {
        // $this->authorize('update', $quiz);

        $question->delete();

        return back()->with('success', 'Question deleted successfully!');
    }
}