<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Notifications\UserActivity;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.register');
    }

    public function create (){
        return view('auth.login');
    }

    public function login(Request $request){
        // Validate the request
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Attempt to authenticate
        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        // Regenerate session for security
        $request->session()->regenerate();

        // Record login activity
        // if (method_exists($request->user(), 'notify')) {
        //     $request->user()->notify(new \App\Notifications\UserLoggedIn([
        //         'ip' => $request->ip(),
        //         'agent' => $request->userAgent()
        //     ]));
        // }

        // Redirect to intended URL or dashboard
        return redirect('/dashboard');
    }

    public function register(Request $request)
    {
        // Custom validation rules
        Validator::extend('strong_password', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $value);
        }, 'The :attribute must contain at least one uppercase letter, one lowercase letter, one number and one special character.');

        $rules = [
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\pL\s\-]+$/u' // Only letters, spaces and hyphens
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users',
                function ($attribute, $value, $fail) {
                    if (substr_count($value, '.') > 3) {
                        $fail('The email contains too many dots.');
                    }
                }
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'strong_password', // Using our custom rule
                'different:name',
                'different:email'
            ],
            'role' => [
                'nullable',
                Rule::in(['student', 'teacher', 'admin']) // Only allow these roles
            ]
        ];

        $messages = [
            'name.regex' => 'The name may only contain letters, spaces and hyphens.',
            'password.different' => 'The password must be different from your name and email.',
            'role.in' => 'The selected role is invalid.'
        ];

        try {
            $validated = $request->validate($rules, $messages);

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'] ?? 'student',
                'join_code' => ($request->role === 'admin') ? \Illuminate\Support\Str::random(8) : null,
            ]);

            // Send verification email
            // $user->sendEmailVerificationNotification();
            // When user registers:
            
            Auth::login($user);
            
            auth()->user()->notify(new UserActivity('Account created', 'completed'));
            return redirect('/dashboard')
                ->with('success', 'Registration successful! Please check your email for verification.');

        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Please correct the errors below.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Registration failed: ' . $e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        // Get the authenticated user before logging out
        $user = $request->user();

        // Record logout activity
        // $user->notify(new UserActivity());

        // Log the user out
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');

    }

    public function dashboard()
    {
        $user = Auth::user();
        $recentActivities = auth()->user()->notifications()
            ->take(5)
            ->get()
            ->map(function ($notification) {
                return (object) [
                    'title' => $notification->data['title'],
                    'status' => $notification->data['status'] ?? 'info',
                    'date' => $notification->created_at->format('M d, Y'),
                    'time' => $notification->created_at->format('h:i A')
                ];
            });

        return view('dashboard', compact('user', 'recentActivities'));
    }
}
