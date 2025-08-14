<nav class="bg-white shadow-sm" x-data="{ open: false }">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 justify-between">
            <!-- Mobile menu button -->
            <div class="flex items-center lg:hidden">
                <button @click="open = !open" type="button" class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
                    <span class="sr-only">Open main menu</span>
                    <i :class="open ? 'fa-times' : 'fa-bars'" class="fas"></i>
                </button>
            </div>
            
            <!-- Logo/Brand -->
            <div class="flex flex-shrink-0 items-center">
                <a href="{{ route('dashboard') }}">
                    <img class="h-8 w-auto" src="{{ asset('images/logo.png') }}" alt="QuizPulse">
                </a>
            </div>
            
            <!-- Desktop Navigation -->
            <div class="hidden lg:ml-6 lg:flex lg:items-center lg:space-x-8">
                <!-- Navigation Links -->
                <a href="{{ route('dashboard') }}" class="inline-flex items-center border-b-2 border-blue-500 px-1 pt-1 text-sm font-medium text-gray-900">Dashboard</a>
                <a href="{{ route('quizzes.index') }}" class="inline-flex items-center border-b-2 border-transparent px-1 pt-1 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700">Quizzes</a>
                <a href="{{ route('results.index') }}" class="inline-flex items-center border-b-2 border-transparent px-1 pt-1 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700">Results</a>
            </div>
            
            <!-- User Profile Dropdown -->
            <div class="hidden lg:ml-4 lg:flex lg:items-center">
                <div class="relative ml-3" x-data="{ open: false }">
                    <div>
                        <button @click="open = !open" type="button" class="flex rounded-full bg-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <span class="sr-only">Open user menu</span>
                            <img class="h-8 w-8 rounded-full" src="{{ Auth::user()->avatar_url ?? asset('images/default-avatar.png') }}" alt="{{ Auth::user()->name }}">
                        </button>
                    </div>
                    
                    <div x-show="open" @click.away="open = false" class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Your Profile</a>
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Sign out</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Mobile menu -->
    <div class="lg:hidden" x-show="open" x-transition>
        <div class="space-y-1 pt-2 pb-3">
            <a href="{{ route('dashboard') }}" class="block border-l-4 border-blue-500 bg-blue-50 py-2 pl-3 pr-4 text-base font-medium text-blue-700">Dashboard</a>
            <a href="{{ route('quizzes.index') }}" class="block border-l-4 border-transparent py-2 pl-3 pr-4 text-base font-medium text-gray-500 hover:border-gray-300 hover:bg-gray-50 hover:text-gray-700">Quizzes</a>
            <a href="{{ route('results.index') }}" class="block border-l-4 border-transparent py-2 pl-3 pr-4 text-base font-medium text-gray-500 hover:border-gray-300 hover:bg-gray-50 hover:text-gray-700">Results</a>
        </div>
        <div class="border-t border-gray-200 pt-4 pb-3">
            <div class="flex items-center px-4">
                <div class="flex-shrink-0">
                    <img class="h-10 w-10 rounded-full" src="{{ Auth::user()->avatar_url ?? asset('images/default-avatar.png') }}" alt="{{ Auth::user()->name }}">
                </div>
                <div class="ml-3">
                    <div class="text-base font-medium text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="text-sm font-medium text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>
            <div class="mt-3 space-y-1">
                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-base font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-800">Your Profile</a>
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="block px-4 py-2 text-base font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-800">Sign out</a>
            </div>
        </div>
    </div>
</nav>
