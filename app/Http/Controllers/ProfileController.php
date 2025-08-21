<?php

namespace App\Http\Controllers;

use App\Models\Attempts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user()->loadCount(['attempts']);
        
        // Calculate activity metrics
        $metrics = [
            'quiz_count' => $user->attempts_count,
            'avg_score' => Attempts::where('user_id', $user->id)
                ->whereNotNull('completed_at')
                ->avg('score') ?? 0,
            'last_activity' => $user->last_login_at 
                ? $user->last_login_at->diffForHumans() 
                : 'No recent activity',
            'member_since' => $user->created_at->format('M d, Y'),
        ];
        
        // Get recent quiz attempts (last 5)
        $recentAttempts = Attempts::with('quiz')
            ->where('user_id', $user->id)
            ->whereNotNull('completed_at')
            ->latest('completed_at')
            ->take(5)
            ->get()
            ->map(function ($attempt) {
                return [
                    'quiz_title' => $attempt->quiz->title,
                    'score' => $attempt->score,
                    'completed_at' => $attempt->completed_at->format('M d, Y \a\t h:i A'),
                    'result_url' => route('quizzes.results', [$attempt->quiz, $attempt]),
                ];
            });
        
        return view('profile.show', [
            'user' => $user,
            'metrics' => $metrics,
            'recentAttempts' => $recentAttempts,
        ]);
    }

    public function edit()
    {
        return view('profile.edit', [
            'user' => Auth::user()
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
        ]);

        $user->update($validated);
        
        return redirect()->route('profile.show')
            ->with('success', 'Profile updated successfully!');
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $user = Auth::user();
        
        // Delete old avatar if exists
        if ($user->avatar_path) {
            Storage::delete($user->avatar_path);
        }
        
        // Store new avatar
        $path = $request->file('avatar')->store('storage/avatars');
        
        $user->update([
            'avatar_path' => $path,
            'avatar_url' => Storage::url($path)
        ]);
        
        return back()
            ->with('success', 'Avatar updated successfully!');
    }
}