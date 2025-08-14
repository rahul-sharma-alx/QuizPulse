@extends('layouts.app')

@section('title', 'All Quizzes')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Available Quizzes</h1>
            @can('create', App\Models\Quizzes::class)
                <a href="{{ route('quizzes.create') }}"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Create New Quiz
                </a>
            @endcan
            @if ($role === 'admin' || $role === 'teacher')
                <a href="{{ route('quizzes.create') }}"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Create New Quiz
                </a>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($quizzes as $quiz)
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition">
                    <div class="p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h2 class="text-xl font-semibold text-gray-800">{{ $quiz->title }}</h2>
                                <p class="text-gray-600 mt-2">{{ $quiz->description }}</p>
                            </div>
                            <span class="px-2 py-1 text-xs rounded-full 
                                {{ $quiz->is_published ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $quiz->is_published ? 'Published' : 'Draft' }}
                            </span>
                        </div>

                        <div class="mt-4 flex justify-between text-sm text-gray-500">
                            <span>{{ $quiz->questions_count }} questions</span>
                            <span>{{ $quiz->duration_minutes }} mins</span>
                        </div>

                        <div class="mt-6 flex space-x-3">
                            <a href="{{ route('quizzes.show', $quiz) }}"
                                class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                                View
                            </a>
                            @can('update', $quiz)
                                <a href="{{ route('quizzes.edit', $quiz) }}"
                                    class="px-3 py-1 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 transition">
                                    Edit
                                </a>
                            @endcan
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-12">
                    <p class="text-gray-500">No quizzes available yet.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $quizzes->links() }}
        </div>
    </div>
@endsection