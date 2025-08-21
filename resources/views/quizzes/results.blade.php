@extends('layouts.app')

@section('title', 'Quiz Results: ' . $quiz->title)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Results Header -->
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Quiz Results</h1>
            <h2 class="text-xl text-gray-600">{{ $quiz->title }}</h2>
            
            <!-- Score Display -->
            <div class="mt-6 inline-block bg-gradient-to-r from-blue-500 to-purple-600 text-white px-8 py-4 rounded-xl shadow-lg">
                <div class="text-sm uppercase tracking-wider">Your Score</div>
                <div class="text-4xl font-bold my-2">{{ $attempt->score }}%</div>
                <div class="text-xs">
                    Completed on {{ $attempt->completed_at->format('F j, Y \a\t g:i A') }}
                </div>
            </div>
        </div>
        
        <!-- Results Breakdown -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            @foreach($quiz->questions as $index => $question)
            @php
                $userAnswer = collect($attempt->answers)->firstWhere('question_id', $question->id);
                $isCorrect = false;
                
                if ($question->type === 'text') {
                    // Text answers would need manual grading
                    $isCorrect = false;
                } elseif ($userAnswer) {
                    $selectedOptions = $userAnswer['selected'] ?? [];
                    $correctOptions = $question->options->where('is_correct', true)->pluck('id')->toArray();
                    
                    if ($question->type === 'single') {
                        $isCorrect = count($selectedOptions) === 1 && in_array($selectedOptions[0], $correctOptions);
                    } else {
                        $isCorrect = empty(array_diff($selectedOptions, $correctOptions)) && 
                                    empty(array_diff($correctOptions, $selectedOptions));
                    }
                }
            @endphp
            <div class="border-b border-gray-200 p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-lg font-medium text-gray-800">
                            <span class="text-gray-500">{{ $index + 1 }}.</span> 
                            {{ $question->text }}
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">{{ $question->marks }} mark(s)</p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-medium 
                        {{ $isCorrect ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $isCorrect ? 'Correct' : 'Incorrect' }}
                    </span>
                </div>
                
                <!-- User's Answer -->
                <div class="mt-4">
                    <p class="text-sm font-medium text-gray-700">Your Answer:</p>
                    @if($question->type === 'text')
                        <p class="mt-1 p-3 bg-gray-50 rounded-lg">{{ $userAnswer['text'] ?? 'No answer provided' }}</p>
                    @else
                        <ul class="mt-2 space-y-1">
                            @forelse($userAnswer['selected'] ?? [] as $optionId)
                                @php $option = $question->options->firstWhere('id', $optionId) @endphp
                                <li class="flex items-start">
                                    <span class="mr-2 mt-1">
                                        @if(in_array($optionId, $question->options->where('is_correct', true)->pluck('id')->toArray()))
                                            <i class="fas fa-check-circle text-green-500"></i>
                                        @else
                                            <i class="fas fa-times-circle text-red-500"></i>
                                        @endif
                                    </span>
                                    <span>{{ $option->text ?? 'Unknown option' }}</span>
                                </li>
                            @empty
                                <li class="text-gray-500">No answer selected</li>
                            @endforelse
                        </ul>
                    @endif
                </div>
                
                <!-- Correct Answer (if incorrect) -->
                @if(!$isCorrect && $question->type !== 'text')
                <div class="mt-3">
                    <p class="text-sm font-medium text-gray-700">Correct Answer:</p>
                    <ul class="mt-1 space-y-1">
                        @foreach($question->options->where('is_correct', true) as $option)
                        <li class="flex items-start">
                            <span class="mr-2 mt-1"><i class="fas fa-check-circle text-green-500"></i></span>
                            <span>{{ $option->text }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif
                
                <!-- Marks Obtained -->
                <div class="mt-3 text-sm">
                    <span class="font-medium text-gray-700">Marks:</span>
                    <span class="{{ $isCorrect ? 'text-green-600' : 'text-red-600' }}">
                        {{ $isCorrect ? $question->marks : 0 }} / {{ $question->marks }}
                    </span>
                </div>
            </div>
            @endforeach
            
            <!-- Footer -->
            <div class="bg-gray-50 px-6 py-4 flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-500">Attempted by</p>
                    <p class="font-medium">{{ $attempt->user->name }}</p>
                </div>
                <div class="space-x-3">
                    <a href="{{ route('quizzes.show', $quiz) }}" 
                       class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition">
                        Back to Quiz
                    </a>
                    @if(auth()->user()->can('retake', $quiz))
                    <form action="{{ route('quizzes.attempts.start', $quiz) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            Retake Quiz
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection