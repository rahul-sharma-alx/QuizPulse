@extends('layouts.app')

@section('title', 'Quiz Results: ' . $quiz->title)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Quiz Results</h1>
            <h2 class="text-xl text-gray-600">{{ $quiz->title }}</h2>
            
            <div class="mt-6">
                <div class="inline-block bg-gradient-to-r from-blue-500 to-purple-600 text-white px-6 py-3 rounded-lg shadow-lg">
                    <div class="text-sm">Your Score</div>
                    <div class="text-3xl font-bold">{{ $attempt->score }}%</div>
                    <div class="text-xs mt-1">
                        Completed on {{ $attempt->completed_at->format('M j, Y g:i A') }}
                    </div>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            @foreach($questions as $question)
            @php
                $userAnswer = $attempt->getQuestionAnswer($question->id);
                $correctOptions = $question->options->where('is_correct', true)->pluck('id')->toArray();
                $isCorrect = false;
                
                if ($question->type === 'text') {
                    // Text answers need manual grading
                    $isCorrect = false; // Default to false unless manually graded
                } elseif ($userAnswer) {
                    $selected = $userAnswer['selected'] ?? [];
                    if ($question->type === 'single') {
                        $isCorrect = count($selected) === 1 && in_array($selected[0], $correctOptions);
                    } else {
                        $isCorrect = empty(array_diff($selected, $correctOptions)) && 
                                    empty(array_diff($correctOptions, $selected));
                    }
                }
            @endphp
            <div class="border-b border-gray-200 p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-lg font-medium text-gray-800">{{ $question->text }}</h3>
                        <p class="text-sm text-gray-500 mt-1">{{ $question->marks }} mark(s) • {{ $question->type_name }}</p>
                    </div>
                    <span class="px-2 py-1 text-xs rounded-full 
                        {{ $isCorrect ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $isCorrect ? 'Correct' : 'Incorrect' }}
                    </span>
                </div>
                
                <div class="mt-4">
                    <p class="text-sm font-medium text-gray-700">Your Answer:</p>
                    @if($question->type === 'text')
                        <p class="mt-1 text-gray-800">{{ $userAnswer['text'] ?? 'No answer provided' }}</p>
                    @else
                        <ul class="mt-1 list-disc list-inside">
                            @forelse($userAnswer['selected'] ?? [] as $optionId)
                                @php $option = $question->options->firstWhere('id', $optionId) @endphp
                                <li class="{{ in_array($optionId, $correctOptions) ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $option->text ?? 'Unknown option' }}
                                </li>
                            @empty
                                <li class="text-gray-500">No answer selected</li>
                            @endforelse
                        </ul>
                    @endif
                    
                    @if(!$isCorrect && $question->type !== 'text')
                        <p class="mt-2 text-sm font-medium text-gray-700">Correct Answer:</p>
                        <ul class="mt-1 list-disc list-inside text-gray-800">
                            @foreach($question->options->where('is_correct', true) as $option)
                                <li>{{ $option->text }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
                
                <div class="mt-3 text-sm text-gray-500">
                    @if($isCorrect)
                        Full marks: {{ $question->marks }} / {{ $question->marks }}
                    @else
                        Marks obtained: 0 / {{ $question->marks }}
                    @endif
                </div>
            </div>
            @endforeach
            
            <div class="bg-gray-50 px-6 py-4 flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-500">Attempted by</p>
                    <p class="font-medium">{{ $attempt->user->name }}</p>
                </div>
                <a href="{{ route('quizzes.show', $quiz) }}" 
                   class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Back to Quiz
                </a>
            </div>
        </div>
    </div>
</div>
@endsection