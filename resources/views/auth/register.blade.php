<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register | QuizPulse</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body class="bg-gradient-to-br from-blue-50 to-purple-50 min-h-screen font-sans">
    <div class="container mx-auto px-4 py-12 flex items-center justify-center">
        <div class="w-full max-w-md">
            <div class="bg-white rounded-xl shadow-2xl overflow-hidden transition-all duration-500 hover:shadow-xl">
                <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-6">
                    <h1 class="text-3xl font-bold text-white text-center">Create Your Account</h1>
                    <p class="text-blue-100 text-center mt-2">Join our community today</p>
                </div>

                <form action="{{url('register')}}" method="POST" enctype="multipart/form-data" class="p-8 space-y-4">
                    @csrf

                    <!-- Name Field -->
                    <div class="space-y-1">
                        <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                        <input type="text" id="name" name="name" required
                            class="w-full px-4 py-3 rounded-lg border {{ $errors->has('name') ? 'border-red-500' : 'border-gray-300' }} focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 placeholder-gray-400"
                            placeholder="John Doe" value="{{ old('name') }}">
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Field -->
                    <div class="space-y-1">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                        <input type="email" id="email" name="email" required
                            class="w-full px-4 py-3 rounded-lg border {{ $errors->has('email') ? 'border-red-500' : 'border-gray-300' }} focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 placeholder-gray-400"
                            placeholder="you@example.com" value="{{ old('email') }}">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div class="space-y-1">
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" id="password" name="password" required
                            class="w-full px-4 py-3 rounded-lg border {{ $errors->has('password') ? 'border-red-500' : 'border-gray-300' }} focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 placeholder-gray-400"
                            placeholder="••••••••">
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- Add password requirements hint -->
                    <div class="bg-blue-50 p-4 rounded-lg mb-4">
                        <h3 class="text-sm font-medium text-blue-800">Password Requirements:</h3>
                        <ul class="mt-1 text-xs text-blue-600 list-disc list-inside">
                            <li>Minimum 8 characters</li>
                            <li>At least one uppercase letter</li>
                            <li>At least one lowercase letter</li>
                            <li>At least one number</li>
                            <li>At least one special character (@$!%*?&)</li>
                            <li>Must be different from your name and email</li>
                        </ul>
                    </div>
                    <!-- Confirm Password Field -->
                    <div class="space-y-1">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm
                            Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 placeholder-gray-400"
                            placeholder="••••••••">
                    </div>

                    <div class="pt-2">
                        <button type="submit"
                            class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium py-3 px-4 rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all duration-300 transform hover:scale-[1.02] shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                            Register Now
                        </button>
                    </div>

                    <div class="text-center text-sm text-gray-500">
                        Already have an account?
                        <a href="#"
                            class="text-blue-600 hover:text-blue-800 font-medium transition-colors duration-200">
                            Sign in
                        </a>
                    </div>
                </form>
            </div>

            <div class="mt-8 text-center text-gray-500 text-sm">
                <p>By registering, you agree to our <a href="#" class="text-blue-600 hover:underline">Terms</a> and <a
                        href="#" class="text-blue-600 hover:underline">Privacy Policy</a></p>
            </div>
        </div>
    </div>
</body>

</html>