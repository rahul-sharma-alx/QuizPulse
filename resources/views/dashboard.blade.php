@extends('layouts.app')

@section('title', 'Dashboard')
@section('header', 'Dashboard Overview')

@section('content')
    <div class="bg-white shadow rounded-lg p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Stats Cards -->
            <div class="bg-blue-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <i class="fas fa-question-circle text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Quizzes</p>
                        <p class="text-2xl font-semibold text-gray-900">24</p>
                    </div>
                </div>
            </div>

            <div class="bg-green-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <i class="fas fa-check-circle text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Completed</p>
                        <p class="text-2xl font-semibold text-gray-900">18</p>
                    </div>
                </div>
            </div>

            <div class="bg-yellow-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                        <i class="fas fa-clock text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Pending</p>
                        <p class="text-2xl font-semibold text-gray-900">4</p>
                    </div>
                </div>
            </div>

            <div class="bg-purple-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                        <i class="fas fa-trophy text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">High Score</p>
                        <p class="text-2xl font-semibold text-gray-900">92%</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="mt-8">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Recent Activity</h2>
            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <ul class="divide-y divide-gray-200">
                    @forelse($recentActivities as $activity)
                        <li>
                            <div class="px-4 py-4 sm:px-6">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-blue-600 truncate">
                                        {{ $activity->title }}
                                    </p>
                                    <div class="ml-2 flex-shrink-0 flex">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $activity->status }}">
                                            {{ ucfirst($activity->status) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="mt-2 sm:flex sm:justify-between">
                                    <div class="flex items-center text-sm text-gray-500">
                                        <i class="fas fa-calendar-alt mr-1.5 h-5 w-5 text-gray-400"></i>
                                        {{ $activity->date }}
                                    </div>
                                    <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                        <i class="fas fa-clock mr-1.5 h-5 w-5 text-gray-400"></i>
                                        {{ $activity->time }}
                                    </div>
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="px-4 py-4 text-center text-gray-500">
                            No recent activities found
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
@endsection