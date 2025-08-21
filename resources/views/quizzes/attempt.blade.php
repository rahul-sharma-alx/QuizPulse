@extends('layouts.app')

@section('title', 'Attempt Quiz: ' . $quiz->title)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Quiz Header -->
        <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">{{ $quiz->title }}</h1>
                <p class="text-gray-600">{{ $quiz->description }}</p>
            </div>
            
            <!-- Timer -->
            <div class="bg-blue-100 text-blue-800 px-4 py-2 rounded-full text-sm font-medium">
                <i class="fas fa-clock mr-1"></i>
                Time remaining: <span id="time-remaining">{{ gmdate('i:s', $timeLeft) }}</span>
            </div>
        </div>
        
        <!-- Quiz Form -->
        <form action="{{ route('quizzes.attempts.submit', [$quiz, $attempt]) }}" method="POST" id="quiz-form">
            @csrf

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                    <strong class="font-bold">Whoops!</strong>
                    <span class="block sm:inline">There were some problems with your submission.</span>
                    <ul class="mt-3 list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <!-- Questions -->
            <div class="space-y-6">
                @foreach($quiz->questions as $index => $question)
                <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
                    <!-- Question Header -->
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <div class="flex justify-between items-start">
                            <h3 class="text-lg font-medium text-gray-800">
                                <span class="text-gray-500">{{ $index + 1 }}.</span> 
                                {{ $question->text }}
                            </h3>
                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">
                                {{ $question->marks }} mark(s)
                            </span>
                        </div>
                        <p class="mt-1 text-sm text-gray-500">
                            {{ $question->type === 'single' ? 'Single Choice' : 
                              ($question->type === 'multiple' ? 'Multiple Choice' : 'Text Answer') }}
                        </p>
                    </div>
                    
                    <!-- Question Body -->
                    <div class="p-6">
                        @if($question->type === 'text')
                            <!-- Text Answer -->
                            <textarea name="answers[{{ $index }}][text]"
                                      rows="3"
                                      class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('answers.' . $index . '.text') border-red-500 @enderror"
                                      placeholder="Type your answer here">{{ old('answers.' . $index . '.text') }}</textarea>
                            @error('answers.' . $index . '.text')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <input type="hidden" name="answers[{{ $index }}][question_id]" value="{{ $question->id }}">
                        @else
                            <!-- Single/Multiple Choice -->
                            <div class="space-y-3">
                                @foreach($question->options as $optionIndex => $option)
                                <div class="flex items-start">
                                    <div class="flex items-center h-5 mt-1">
                                        <input type="{{ $question->type === 'single' ? 'radio' : 'checkbox' }}" 
                                               name="answers[{{ $index }}][selected]{{ $question->type === 'multiple' ? '[]' : '' }}" 
                                               id="option-{{ $option->id }}" 
                                               value="{{ $option->id }}"
                                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded @error('answers.' . $index . '.selected') border-red-500 @enderror"
                                               {{ ($question->type === 'single' && old('answers.' . $index . '.selected') == $option->id) ? 'checked' : '' }}
                                               {{ ($question->type === 'multiple' && is_array(old('answers.' . $index . '.selected')) && in_array($option->id, old('answers.' . $index . '.selected'))) ? 'checked' : '' }}>
                                    </div>
                                    <label for="option-{{ $option->id }}" class="ml-3 block text-gray-700">
                                        <span class="text-gray-500 mr-2">{{ chr(65 + $optionIndex) }}.</span>
                                        {{ $option->text }}
                                    </label>
                                </div>
                                @endforeach
                                @error('answers.' . $index . '.selected')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                <input type="hidden" name="answers[{{ $index }}][question_id]" value="{{ $question->id }}">
                            </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Submit Button -->
            <div class="mt-8 text-center">
                <button type="submit" 
                        class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium shadow-md">
                    <i class="fas fa-paper-plane mr-2"></i> Submit Quiz
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Timer Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const timerElement = document.getElementById('time-remaining');
    const quizForm = document.getElementById('quiz-form');
    let timeLeft = {{ $timeLeft }};
    
    // Update timer every second
    const timerInterval = setInterval(function() {
        timeLeft--;
        
        // Format time as MM:SS
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        timerElement.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        
        // Submit form when time runs out
        if (timeLeft <= 0) {
            clearInterval(timerInterval);
            quizForm.submit();
        }
        
        // Change color when under 1 minute
        if (timeLeft <= 60) {
            timerElement.parentElement.classList.remove('bg-blue-100', 'text-blue-800');
            timerElement.parentElement.classList.add('bg-red-100', 'text-red-800');
        }
    }, 1000);
    
    // Prevent accidental navigation
    window.addEventListener('beforeunload', function(e) {
        if (timeLeft > 0) {
            e.preventDefault();
            e.returnValue = 'You have an ongoing quiz attempt. Are you sure you want to leave?';
        }
    });
});
</script>
@endsection
