@extends('layouts.app')

@section('title', $quiz->title)

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- resources/views/quizzes/show.blade.php -->
        @if(auth()->check() && auth()->user()->id !== $quiz->admin_id)
            <div class="mt-8 text-center">
                <form action="{{ route('quizzes.attempts.start', $quiz) }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium shadow-lg">
                        <i class="fas fa-play mr-2"></i> Start Quiz
                    </button>
                </form>

                @if($activeAttempt = auth()->user()->attempts()->where('quiz_id', $quiz->id)->whereNull('completed_at')->first())
                    <div class="mt-4 text-sm text-gray-600">
                        You have an ongoing attempt.
                        <a href="{{ route('quizzes.attempts.show', [$quiz, $activeAttempt]) }}"
                            class="text-blue-600 hover:text-blue-800 font-medium">
                            Continue Attempt
                        </a>
                    </div>
                @endif
            </div>
        @endif
        <div class="max-w-4xl mx-auto">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">{{ $quiz->title }}</h1>
                    <p class="text-gray-600 mt-2">{{ $quiz->description }}</p>
                </div>
                <div class="flex space-x-3">
                    @if ($role!='admin' || $role!='teacher')
                        @can('update', $quiz)
                            <a href="{{ route('quizzes.edit', $quiz) }}"
                                class="px-3 py-1 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 transition">
                                Edit
                            </a>
                            <form action="{{ route('quizzes.toggle-publish', $quiz) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="px-3 py-1 {{ $quiz->is_published ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }} rounded hover:opacity-90 transition">
                                    {{ $quiz->is_published ? 'Unpublish' : 'Publish' }}
                                </button>
                            </form>
                        @endcan
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                        <div>
                            <p class="text-sm text-gray-500">Questions</p>
                            <p class="text-xl font-semibold">{{ $quiz->questions->count() }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Duration</p>
                            <p class="text-xl font-semibold">{{ $quiz->duration_minutes }} mins</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Attempts</p>
                            <p class="text-xl font-semibold">{{ $quiz->attempts->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold text-gray-800">Questions</h2>
                        @can('update', $quiz)
                            <a href="{{ route('questions.create', $quiz) }}"
                                class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                                Add Question
                            </a>
                        @endcan
                        @if ($role == 'admin' || $role == 'teacher')
                            <a href="{{ route('questions.create', $quiz) }}"
                                class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                                Add Question
                            </a>
                        @endif
                    </div>

                    <div class="space-y-4">
                        @forelse($quiz->questions as $question)
                            <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="font-medium">{{ $question->text }}</h3>
                                        <p class="text-sm text-gray-500 mt-1">{{ $question->options->count() }} options</p>
                                    </div>
                                    @if ($role == 'admin' || $role == 'teacher')
                                        <div class="flex space-x-2">
                                            <a href="{{ route('questions.edit', [$quiz, $question]) }}"
                                                class="text-blue-600 hover:text-blue-800">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>                                    
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center py-6">No questions added yet.</p>
                        @endforelse
                    </div>
                </div>

                @if($quiz->attempts->count() > 0)
                    <div class="p-6 border-t border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Recent Attempts</h2>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            User</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Score</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Date</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($quiz->attempts->take(5) as $attempt)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <img class="h-10 w-10 rounded-full"
                                                            src="{{ $attempt->user->avatar_url ?? asset('images/default-avatar.png') }}"
                                                            alt="{{ $attempt->user->name }}">
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">{{ $attempt->user->name }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                                {{ $attempt->score >= 70 ? 'bg-green-100 text-green-800' : ($attempt->score >= 50 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                    {{ $attempt->score }}%
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $attempt->created_at->diffForHumans() }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection