@extends('layouts.app')

@section('title', 'Edit Question')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('quizzes.show', $quiz) }}" class="text-blue-600 hover:text-blue-800">
                ← Back to Quiz
            </a>
            <h1 class="text-2xl font-bold text-gray-800 mt-2">Edit Question</h1>
            <p class="text-gray-600">For: {{ $quiz->title }}</p>
        </div>
        
        <form action="{{ route('questions.update', [$quiz, $question]) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="mb-6">
                    <label for="text" class="block text-sm font-medium text-gray-700 mb-1">Question Text</label>
                    <textarea id="text" name="text" rows="3" required
                              class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Enter your question here">{{ old('text', $question->text) }}</textarea>
                    @error('text')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Question Type</label>
                        <select id="type" name="type" required
                                class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500 question-type-selector">
                            @foreach($types as $value => $label)
                                <option value="{{ $value }}" {{ old('type', $question->type) == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="marks" class="block text-sm font-medium text-gray-700 mb-1">Marks</label>
                        <input type="number" id="marks" name="marks" min="1" required
                               class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500"
                               value="{{ old('marks', $question->marks) }}">
                        @error('marks')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Options Section (shown for single/multiple choice) -->
                <div id="options-section" class="mb-6 {{ in_array(old('type', $question->type), ['single', 'multiple']) ? '' : 'hidden' }}">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Options</label>
                    <div id="options-container" class="space-y-3">
                        @php
                            $oldOptions = old('options', $question->options->pluck('text')->toArray());
                            $oldCorrect = old('correct_options', $question->options->filter(fn($opt) => $opt->is_correct)->keys()->toArray());
                        @endphp
                        
                        @foreach($oldOptions as $index => $option)
                        <div class="option-item flex items-center space-x-3">
                            <input type="text" name="options[]" required
                                   class="flex-1 px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                   value="{{ $option }}"
                                   placeholder="Option text">
                            <input type="checkbox" name="correct_options[]" value="{{ $index }}"
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                   {{ in_array($index, $oldCorrect) ? 'checked' : '' }}>
                            <span class="text-sm text-gray-500">Correct</span>
                            <button type="button" class="remove-option text-red-600 hover:text-red-800">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        @endforeach
                    </div>
                    
                    <button type="button" id="add-option" 
                            class="mt-3 px-3 py-1 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 transition">
                        <i class="fas fa-plus mr-1"></i> Add Option
                    </button>
                    
                    @error('options')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @error('correct_options')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex justify-between items-center">
                    <button type="button" onclick="confirmDelete()"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        Delete Question
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Update Question
                    </button>
                </div>
            </div>
        </form>
        
        <form id="delete-form" action="{{ route('questions.destroy', [$quiz, $question]) }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelector = document.querySelector('.question-type-selector');
    const optionsSection = document.getElementById('options-section');
    const optionsContainer = document.getElementById('options-container');
    const addOptionBtn = document.getElementById('add-option');
    
    // Toggle options section based on question type
    typeSelector.addEventListener('change', function() {
        if (this.value === 'text') {
            optionsSection.classList.add('hidden');
        } else {
            optionsSection.classList.remove('hidden');
        }
    });
    
    // Add new option
    addOptionBtn.addEventListener('click', function() {
        const optionCount = document.querySelectorAll('.option-item').length;
        const newOption = document.createElement('div');
        newOption.className = 'option-item flex items-center space-x-3 mt-3';
        newOption.innerHTML = `
            <input type="text" name="options[]" required
                   class="flex-1 px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500"
                   placeholder="Option text">
            <input type="checkbox" name="correct_options[]" value="${optionCount}"
                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
            <span class="text-sm text-gray-500">Correct</span>
            <button type="button" class="remove-option text-red-600 hover:text-red-800">
                <i class="fas fa-times"></i>
            </button>
        `;
        optionsContainer.appendChild(newOption);
    });
    
    // Remove option
    optionsContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-option') || 
            e.target.parentElement.classList.contains('remove-option')) {
            const optionItem = e.target.closest('.option-item');
            if (document.querySelectorAll('.option-item').length > 2) {
                optionItem.remove();
                // Update the value attributes of remaining checkboxes
                document.querySelectorAll('.option-item input[type="checkbox"]').forEach((checkbox, index) => {
                    checkbox.value = index;
                });
            }
        }
    });
});

function confirmDelete() {
    if (confirm('Are you sure you want to delete this question?')) {
        document.getElementById('delete-form').submit();
    }
}
</script>
@endsection