<?php

namespace App\Policies;

use App\Models\Quizzes;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class QuizPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Quizzes $quiz)
    {
        return true;
    }

    public function create(User $user)
    {
        return $user->role === 'admin' || $user->role === 'teacher';
    }

    public function update(User $user, Quizzes $quiz)
    {
        return $user->role === 'admin' || $user->id === $quiz->admin_id;
    }

    public function delete(User $user, Quizzes $quiz)
    {
        return $user->role === 'admin' || $user->id === $quiz->admin_id;
    }
}
