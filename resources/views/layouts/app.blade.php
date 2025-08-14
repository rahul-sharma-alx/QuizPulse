<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') | {{ config('app.name') }}</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @stack('styles')
</head>

<body class="h-full">
    <div class="min-h-full">
        <!-- Include Navbar -->
        @include('layouts.navbar')

        <!-- Include Sidebar -->
        @include('layouts.sidebar')

        <!-- Main Content -->
        <div class="lg:pl-64 flex flex-col flex-1">
            <main class="flex-1 pb-8">
                <!-- Page Header -->
                <div class="bg-white shadow">
                    <div class="px-4 sm:px-6 lg:px-8 py-6">
                        <div class="flex justify-between items-center">
                            <h1 class="text-2xl font-bold text-gray-900">@yield('header')</h1>
                            @yield('header-actions')
                        </div>
                    </div>
                </div>

                <!-- Page Content -->
                <div class="px-4 sm:px-6 lg:px-8 py-6">
                    @yield('content')
                </div>
            </main>
        </div>
        {{-- <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form> --}}
    </div>

    @stack('scripts')
</body>

</html>