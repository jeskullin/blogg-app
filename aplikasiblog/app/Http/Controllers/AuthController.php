<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // Sesuaikan dengan nama model User Anda

class AuthController extends Controller
{
    // Register user
    public function register(Request $request)
    {
        // Validate fields
        $attrs = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        // Create user
        $user = User::create([
            'name' => $attrs['name'],
            'email' => $attrs['email'],
            'password' => bcrypt($attrs['password']),
        ]);

        // Return user & token in response
        return response([
            'user' => $user,
            'token' => $user->createToken('secret')->plainTextToken
        ], 200);
    }

    // Login user
    public function login(Request $request)
    {
        // Validate fields
        $attrs = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        // Attempt login
        if (!Auth::attempt($attrs)) {
            return response([
                'message' => 'Invalid login.'
            ], 403);
        }

        // Return user & token in response
        return response([
            'user' => auth()->user(),
            'token' => auth()->user()->createToken('secret')->plainTextToken
        ], 200);
    }

    // Logout user
    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response([
            'message' => 'Logout Success.'
        ], 200);
    }
    
    // Get user
    public function user()
    {
        return response([
            'user' => auth()->user()
        ], 200);
    }
}
