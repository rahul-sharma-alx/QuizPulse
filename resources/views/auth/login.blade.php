<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login | QuizPulse</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gradient-to-br from-blue-50 to-purple-50 min-h-screen font-sans">
    <div class="container mx-auto px-4 py-12 flex items-center justify-center">
        <div class="w-full max-w-md">
            <div class="bg-white rounded-xl shadow-2xl overflow-hidden transition-all duration-500 hover:shadow-xl">
                <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-6">
                    <h1 class="text-3xl font-bold text-white text-center">Welcome Back</h1>
                    <p class="text-blue-100 text-center mt-2">Sign in to continue your journey</p>
                </div>

                <form action="{{ route('login') }}" method="POST" class="p-8 space-y-4">
                    @csrf

                    <!-- Email Field -->
                    <div class="space-y-1">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                        <input type="email" id="email" name="email" required autocomplete="email" autofocus
                            class="w-full px-4 py-3 rounded-lg border {{ $errors->has('email') ? 'border-red-500' : 'border-gray-300' }} focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 placeholder-gray-400"
                            placeholder="you@example.com" value="{{ old('email') }}">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div class="space-y-1">
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <div class="relative">
                            <input type="password" id="password" name="password" required autocomplete="current-password"
                                class="w-full px-4 py-3 rounded-lg border {{ $errors->has('password') ? 'border-red-500' : 'border-gray-300' }} focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 placeholder-gray-400"
                                placeholder="••••••••">
                            <button type="button" onclick="togglePassword()"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                <i id="eye-icon" class="far fa-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember" name="remember" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <label for="remember" class="ml-2 block text-sm text-gray-700">Remember me</label>
                        </div>
                        <div class="text-sm">
                            {{-- <a href="{{ route('password.request') }}" class="font-medium text-blue-600 hover:text-blue-500">Forgot password?</a> --}}
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit"
                            class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium py-3 px-4 rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all duration-300 transform hover:scale-[1.02] shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                            Sign In
                        </button>
                    </div>

                    <div class="text-center text-sm text-gray-500">
                        Don't have an account?
                        <a href="{{ route('register') }}"
                            class="text-blue-600 hover:text-blue-800 font-medium transition-colors duration-200">
                            Register now
                        </a>
                    </div>
                </form>
            </div>

            <div class="mt-8 text-center text-gray-500 text-sm">
                <p>By signing in, you agree to our <a href="#" class="text-blue-600 hover:underline">Terms</a> and <a
                        href="#" class="text-blue-600 hover:underline">Privacy Policy</a></p>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>

</html>