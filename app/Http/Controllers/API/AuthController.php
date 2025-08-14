<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function index(){
        return view ('register');
    }
    public function register(RegisterRequest $req)
    {
        $user = User::create([
            'name' => $req->name,
            'email' => $req->email,
            'password' => bcrypt($req->password),
            'role' => $req->role ?? 'student',
            'join_code' => ($req->role === 'admin') ? Str::random(8) : null,
        ]);

        $token = $user->createToken('api-token')->plainTextToken;
        return response()->json(['user' => $user, 'token' => $token], 201);
    }

    public function login(LoginRequest $req)
    {
        $user = User::where('email', $req->email)->first();
        if (!$user || !Hash::check($req->password, $user->password)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }
        $token = $user->createToken('api-token')->plainTextToken;
        return response()->json(['user' => $user, 'token' => $token]);
    }

    public function logout(Request $req)
    {
        $req->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    }
}
