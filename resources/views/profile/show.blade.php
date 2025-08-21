{{-- resources/views/profile/show.blade.php --}}
@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
        <!-- Profile Header -->
        <div class="bg-gradient-to-r from-blue-500 to-purple-600 p-6 text-white">
            <div class="flex flex-col sm:flex-row items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <img class="h-16 w-16 rounded-full border-4 border-white/20" 
                             src="{{ Auth::user()->avatar_url ?? asset('images/default-avatar.png') }}" 
                             alt="{{ Auth::user()->name }}">
                    </div>
                    <div>
                        <h2 class="text-xl font-bold">{{ Auth::user()->name }}</h2>
                        <p class="text-blue-100 text-sm">{{ Auth::user()->email }}</p>
                    </div>
                </div>
                <div class="mt-4 sm:mt-0">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-white/20">
                        <i class="fas fa-user-shield mr-1"></i>
                        {{ ucfirst(Auth::user()->role) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Profile Content -->
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Personal Information -->
                <div>
                    <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200 mb-4 border-b pb-2">
                        <i class="fas fa-user-circle mr-2"></i>Personal Information
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Full Name</p>
                            <p class="text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Email Address</p>
                            <p class="text-gray-800 dark:text-gray-200">{{ Auth::user()->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Account Type</p>
                            <p class="text-gray-800 dark:text-gray-200 capitalize">{{ Auth::user()->role }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Member Since</p>
                            <p class="text-gray-800 dark:text-gray-200">{{ Auth::user()->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Activity Summary -->
                <div>
                    <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200 mb-4 border-b pb-2">
                        <i class="fas fa-chart-line mr-2"></i>Activity Summary
                    </h3>
                    <div class="space-y-4">
                        <div class="bg-blue-50 dark:bg-blue-900/30 p-4 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-blue-800 dark:text-blue-200">Quizzes Taken</p>
                                    <p class="text-2xl font-bold text-blue-600 dark:text-blue-300">
                                        {{ Auth::user()->attempts()->count() }}
                                    </p>
                                </div>
                                <i class="fas fa-question-circle text-blue-400 text-2xl"></i>
                            </div>
                        </div>
                        <div class="bg-purple-50 dark:bg-purple-900/30 p-4 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-purple-800 dark:text-purple-200">Average Score</p>
                                    <p class="text-2xl font-bold text-purple-600 dark:text-purple-300">
                                        {{ number_format(Auth::user()->attempts()->avg('score') ?? 0, 1) }}%
                                    </p>
                                </div>
                                <i class="fas fa-star text-purple-400 text-2xl"></i>
                            </div>
                        </div>
                        <div class="bg-green-50 dark:bg-green-900/30 p-4 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-green-800 dark:text-green-200">Last Activity</p>
                                    <p class="text-gray-800 dark:text-gray-200">
                                        @if(Auth::user()->last_login_at)
                                            {{ Auth::user()->last_login_at->diffForHumans() }}
                                        @else
                                            Never logged in
                                        @endif
                                    </p>
                                </div>
                                <i class="fas fa-clock text-green-400 text-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="mt-8">
                <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200 mb-4 border-b pb-2">
                    <i class="fas fa-history mr-2"></i>Recent Activity
                </h3>
                <div class="bg-white dark:bg-gray-700 rounded-lg shadow overflow-hidden">
                    <ul class="divide-y divide-gray-200 dark:divide-gray-600">
                        @forelse(Auth::user()->attempts()->latest()->take(5)->get() as $attempt)
                        <li class="p-4 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                            <div class="flex items-center justify-between">
                                <div>
                                    <a href="{{ route('quizzes.results', [$attempt->quiz, $attempt]) }}" 
                                       class="font-medium text-blue-600 dark:text-blue-400 hover:underline">
                                        {{ $attempt->quiz->title }}
                                    </a>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $attempt->completed_at->format('M d, Y \a\t h:i A') }}
                                    </p>
                                </div>
                                <span class="px-3 py-1 rounded-full text-xs font-medium 
                                    {{ $attempt->score >= 70 ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 
                                       ($attempt->score >= 50 ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 
                                       'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200') }}">
                                    {{ $attempt->score }}%
                                </span>
                            </div>
                        </li>
                        @empty
                        <li class="p-4 text-center text-gray-500 dark:text-gray-400">
                            No recent activity found
                        </li>
                        @endforelse
                    </ul>
                </div>
                @if(Auth::user()->attempts()->count() > 5)
                <div class="mt-4 text-right">
                    <a href="{{ route('profile.activity') }}" 
                       class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                        View All Activity
                    </a>
                </div>
                @endif
            </div>

            <!-- Actions -->
            <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-3">
                <a href="{{ route('profile.edit') }}" 
                   class="px-4 py-2 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-lg hover:opacity-90 transition-opacity text-center">
                    <i class="fas fa-edit mr-2"></i>Edit Profile
                </a>
                <form method="POST" action="{{ route('logout') }}" class="w-full sm:w-auto">
                    @csrf
                    <button type="submit" 
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors text-center">
                        <i class="fas fa-sign-out-alt mr-2"></i>Log Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection