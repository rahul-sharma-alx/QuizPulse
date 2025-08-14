@extends('layouts.app')

@section('title', 'Create New Quiz')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Create New Quiz</h1>
        
        <form action="{{ route('quizzes.store') }}" method="POST">
            @csrf
            
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="mb-6">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Quiz Title</label>
                    <input type="text" id="title" name="title" required
                           class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500"
                           value="{{ old('title') }}">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea id="description" name="description" rows="3"
                              class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500">{{ old('description') }}</textarea>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="duration_minutes" class="block text-sm font-medium text-gray-700 mb-1">Duration (minutes)</label>
                        <input type="number" id="duration_minutes" name="duration_minutes" min="1" required
                               class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500"
                               value="{{ old('duration_minutes', 30) }}">
                        @error('duration_minutes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" id="is_published" name="is_published" 
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                               {{ old('is_published') ? 'checked' : '' }}>
                        <label for="is_published" class="ml-2 block text-sm text-gray-700">Publish immediately</label>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('quizzes.index') }}" 
                       class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Create Quiz
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection