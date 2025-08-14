<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Quizzes;
use App\Policies\QuizPolicy;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    protected $policies = [
        User::class => UserPolicy::class,
        Quizzes::class => QuizPolicy::class,
        // Add other model-policy mappings here
    ];

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Define the 'view-users' gate
        Gate::define('view-users', function ($user) {
            // Only users with 'admin' or 'teacher' role can view users
            return in_array($user->role, ['admin', 'teacher']);
        });
    }
}
