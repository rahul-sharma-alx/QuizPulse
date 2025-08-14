@extends('layouts.app')

@section('title', 'Take Quiz: ' . $quiz->title)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="mb-6 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">{{ $quiz->title }}</h1>
            <div class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                Time remaining: <span id="time-remaining">{{ gmdate('i:s', $timeLeft) }}</span>
            </div>
        </div>
        
        <form action="{{ route('quizzes.attempts.submit', [$quiz, $attempt]) }}" method="POST" id="quiz-form">
            @csrf
            
            <div class="space-y-8">
                @foreach($quiz->questions as $index => $question)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="mb-4">
                        <h3 class="text-lg font-medium text-gray-800">
                            <span class="text-gray-500">{{ $index + 1 }}.</span> 
                            {{ $question->text }}
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">{{ $question->marks }} mark(s) • {{ ucfirst($question->type) }}</p>
                    </div>
                    
                    @php
                        $userAnswer = $attempt->getQuestionAnswer($question->id);
                    @endphp
                    
                    @if($question->type === 'text')
                        <textarea name="answers[{{ $index }}][text]"
                                  class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Type your answer here">{{ $userAnswer['text'] ?? '' }}</textarea>
                        <input type="hidden" name="answers[{{ $index }}][question_id]" value="{{ $question->id }}">
                    @else
                        <div class="space-y-3">
                            @foreach($question->options as $option)
                            <div class="flex items-center">
                                <input type="{{ $question->type === 'single' ? 'radio' : 'checkbox' }}" 
                                       name="answers[{{ $index }}][selected]{{ $question->type === 'multiple' ? '[]' : '' }}" 
                                       id="option-{{ $option->id }}" 
                                       value="{{ $option->id }}"
                                       @if($userAnswer && in_array($option->id, $userAnswer['selected'] ?? [])) checked @endif
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                <label for="option-{{ $option->id }}" class="ml-3 block text-gray-700">
                                    {{ $option->text }}
                                </label>
                            </div>
                            @endforeach
                            <input type="hidden" name="answers[{{ $index }}][question_id]" value="{{ $question->id }}">
                        </div>
                    @endif
                </div>
                @endforeach
            </div>
            
            <div class="mt-8 text-center">
                <button type="submit" 
                        class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium">
                    Submit Quiz
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Timer functionality
    let timeLeft = {{ $timeLeft }};
    const timerElement = document.getElementById('time-remaining');
    const quizForm = document.getElementById('quiz-form');
    
    const timer = setInterval(function() {
        timeLeft--;
        
        if (timeLeft <= 0) {
            clearInterval(timer);
            quizForm.submit();
            return;
        }
        
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        timerElement.textContent = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
    }, 1000);
});
</script>
@endsection