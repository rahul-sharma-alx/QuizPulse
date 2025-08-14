<div class="hidden lg:fixed lg:inset-y-0 lg:flex lg:w-64 lg:flex-col lg:border-r lg:border-gray-200 lg:bg-white lg:pb-4 lg:pt-5">
    <!-- Sidebar component, hidden on mobile -->
    <div class="flex flex-shrink-0 items-center px-6">
        <img class="h-8 w-auto" src="{{ asset('images/logo.png') }}" alt="QuizPulse">
    </div>
    
    <!-- Sidebar Navigation -->
    <nav class="mt-5 flex flex-1 flex-col overflow-y-auto">
        <div class="space-y-1 px-2">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" @class([
                'group flex items-center rounded-md px-2 py-2 text-sm font-medium',
                'bg-blue-50 text-blue-600' => request()->routeIs('dashboard'),
                'text-gray-600 hover:bg-gray-50 hover:text-gray-900' => !request()->routeIs('dashboard'),
            ])>
                <i class="fas fa-tachometer-alt mr-3 text-gray-400 group-hover:text-gray-500"></i>
                Dashboard
            </a>
            
            <!-- Quizzes -->
            <a href="{{ route('quizzes.index') }}" @class([
                'group flex items-center rounded-md px-2 py-2 text-sm font-medium',
                'bg-blue-50 text-blue-600' => request()->routeIs('quizzes.*'),
                'text-gray-600 hover:bg-gray-50 hover:text-gray-900' => !request()->routeIs('quizzes.*'),
            ])>
                <i class="fas fa-question-circle mr-3 text-gray-400 group-hover:text-gray-500"></i>
                Quizzes
            </a>
            
            <!-- Results -->
            <a href="{{ route('results.index') }}" @class([
                'group flex items-center rounded-md px-2 py-2 text-sm font-medium',
                'bg-blue-50 text-blue-600' => request()->routeIs('results.*'),
                'text-gray-600 hover:bg-gray-50 hover:text-gray-900' => !request()->routeIs('results.*'),
            ])>
                <i class="fas fa-chart-bar mr-3 text-gray-400 group-hover:text-gray-500"></i>
                Results
            </a>
            
            <!-- Users (Admin Only) -->
            {{-- @can('view-users')
            <a href="{{ route('users.index') }}" @class([
                'group flex items-center rounded-md px-2 py-2 text-sm font-medium',
                'bg-blue-50 text-blue-600' => request()->routeIs('users.*'),
                'text-gray-600 hover:bg-gray-50 hover:text-gray-900' => !request()->routeIs('users.*'),
            ])>
                <i class="fas fa-users mr-3 text-gray-400 group-hover:text-gray-500"></i>
                Users
            </a>
            @endcan
            
            <!-- Settings -->
            <a href="{{ route('settings') }}" @class([
                'group flex items-center rounded-md px-2 py-2 text-sm font-medium',
                'bg-blue-50 text-blue-600' => request()->routeIs('settings'),
                'text-gray-600 hover:bg-gray-50 hover:text-gray-900' => !request()->routeIs('settings'),
            ])>
                <i class="fas fa-cog mr-3 text-gray-400 group-hover:text-gray-500"></i>
                Settings
            </a> --}}
        </div>
        
        <!-- Bottom Section -->
        <div class="mt-auto px-6 py-4">
            <div class="flex items-center">
                <img class="h-9 w-9 rounded-full" src="{{ Auth::user()->avatar_url ?? asset('images/default-avatar.png') }}" alt="{{ Auth::user()->name }}">
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-700">{{ Auth::user()->name }}</p>
                    <a href="{{ route('profile.edit') }}" class="text-xs font-medium text-blue-600 hover:text-blue-500">View profile</a>
                </div>
            </div>
        </div>
    </nav>
</div>