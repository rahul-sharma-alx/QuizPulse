{{-- views/profile/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Profile')

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
                        <div class="absolute bottom-0 right-0 bg-white rounded-full p-1 shadow-md">
                            <label for="avatar-upload" class="cursor-pointer">
                                <i class="fas fa-camera text-blue-600 text-sm"></i>
                                <input id="avatar-upload" type="file" name="avatar" class="hidden" accept="image/*">
                            </label>
                        </div>
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

        <!-- Profile Form -->
        <div class="p-6">
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" id="profile-form">
                @csrf
                @method('PUT')

                <!-- Name Field -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Full Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name', Auth::user()->name) }}"
                           class="w-full px-4 py-2 rounded-lg border {{ $errors->has('name') ? 'border-red-500' : 'border-gray-300' }} focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                           required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email Field -->
                <div class="mb-6">
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email', Auth::user()->email) }}"
                           class="w-full px-4 py-2 rounded-lg border {{ $errors->has('email') ? 'border-red-500' : 'border-gray-300' }} focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                           required>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Fields -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Password</label>
                    <div class="space-y-4">
                        <div>
                            <label for="current_password" class="sr-only">Current Password</label>
                            <input type="password" id="current_password" name="current_password" 
                                   placeholder="Current password (leave blank to keep unchanged)"
                                   class="w-full px-4 py-2 rounded-lg border {{ $errors->has('current_password') ? 'border-red-500' : 'border-gray-300' }} focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                            @error('current_password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="new_password" class="sr-only">New Password</label>
                            <input type="password" id="new_password" name="new_password" 
                                   placeholder="New password (leave blank to keep unchanged)"
                                   class="w-full px-4 py-2 rounded-lg border {{ $errors->has('new_password') ? 'border-red-500' : 'border-gray-300' }} focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                            @error('new_password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="new_password_confirmation" class="sr-only">Confirm New Password</label>
                            <input type="password" id="new_password_confirmation" name="new_password_confirmation" 
                                   placeholder="Confirm new password"
                                   class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-3">
                    <a href="{{ route('dashboard') }}" 
                       class="px-4 py-2 border border-gray-300 rounded-lg text-center text-gray-700 hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-lg hover:opacity-90 transition-opacity shadow-md">
                        Save Changes
                    </button>
                </div>
            </form>

            <!-- Avatar Upload Form -->
            <form id="avatar-form" action="{{ route('profile.avatar.update') }}" method="POST" enctype="multipart/form-data" class="hidden">
                @csrf
                @method('PUT')
                <input type="file" name="avatar" id="hidden-avatar-upload">
            </form>
        </div>
    </div>
</div>

<!-- JavaScript for Avatar Upload -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle avatar upload
        const avatarUpload = document.getElementById('avatar-upload');
        const hiddenAvatarUpload = document.getElementById('hidden-avatar-upload');
        const avatarForm = document.getElementById('avatar-form');
        
        avatarUpload.addEventListener('change', function(e) {
            if (e.target.files.length > 0) {
                hiddenAvatarUpload.files = e.target.files;
                avatarForm.submit();
            }
        });

        // Preview avatar before upload (optional)
        hiddenAvatarUpload.addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    document.querySelector('.rounded-full').src = event.target.result;
                }
                reader.readAsDataURL(e.target.files[0]);
            }
        });
    });
</script>
@endsection