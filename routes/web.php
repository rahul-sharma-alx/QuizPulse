<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\QuestionController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('register', [AuthController::class, 'index'])->name('register');
Route::get('login', [AuthController::class, 'create']);
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('register', [AuthController::class, 'register']);
Route::post('logout',[AuthController::class, 'logout'])->name('logout');
// Route::get('profile', [AuthController::class, 'loginForm'])->name('login');

Route::middleware(['auth'])->group(function ()  {
    Route::get('dashboard', [AuthController::class, 'dashboard'])->name('dashboard');

    // Quizzes
    Route::get('/quizzes', [QuizController::class, 'index'])->name('quizzes.index');
    Route::get('/quizzes/create', [QuizController::class, 'create'])->name('quizzes.create');
    Route::post('/quizzes', [QuizController::class, 'store'])->name('quizzes.store');
    Route::get('/quizzes/{quiz}', [QuizController::class, 'show'])->name('quizzes.show');
    Route::get('/quizzes/{quiz}/edit', [QuizController::class, 'edit'])->name('quizzes.edit');
    Route::put('/quizzes/{quiz}', [QuizController::class, 'update'])->name('quizzes.update');
    Route::delete('/quizzes/{quiz}', [QuizController::class, 'destroy'])->name('quizzes.destroy');
    Route::patch('/quizzes/{quiz}/toggle-publish', [QuizController::class, 'togglePublish'])->name('quizzes.toggle-publish');

    // Results
    Route::get('/results', [ResultController::class, 'index'])->name('results.index');

    // Questions
    Route::get('/quizzes/{quiz}/questions/create', [QuestionController::class, 'create'])->name('questions.create');
    Route::post('/quizzes/{quiz}/questions', [QuestionController::class, 'store'])->name('questions.store');
    Route::get('/quizzes/{quiz}/questions/{question}/edit', [QuestionController::class, 'edit'])->name('questions.edit');
    Route::put('/quizzes/{quiz}/questions/{question}', [QuestionController::class, 'update'])->name('questions.update');
    Route::delete('/quizzes/{quiz}/questions/{question}', [QuestionController::class, 'destroy'])->name('questions.destroy');
});



// Profile routes (protected by auth middleware)
Route::middleware(['auth'])->group(function () {
    // Show profile
    Route::get('/profile', [ProfileController::class, 'show'])
        ->name('profile.show');
    
    // Edit profile form
    Route::get('/profile/edit', [ProfileController::class, 'edit'])
        ->name('profile.edit');
    
    // Update profile
    Route::put('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
    
    // Update profile picture
    Route::put('/profile/avatar', [ProfileController::class, 'updateAvatar'])
        ->name('profile.avatar.update');
});
