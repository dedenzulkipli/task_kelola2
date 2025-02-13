<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\UserRequest;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(LoginRequest $request)
    {
        $user = \App\Models\User::where('email', $request->email)->first();
    
        if (!$user) {
            return response()->json([
                'errors' => ['email' => ['Email tidak terdaftar!']]
            ], 422);
        }
    
        if (!Auth::attempt($request->only('email', 'password'), $request->remember)) {
            return response()->json([
                'errors' => ['password' => ['Password salah!']]
            ], 422);
        }
    
        if ($user->status == 0) {
            Auth::logout();
            return response()->json(['message' => 'Akun Anda belum aktif. Silakan hubungi Admin.'], 403);
        }
    
        $request->session()->regenerate();
        return response()->json([
            'message' => 'Login berhasil!',
            'username' => $user->username,
            'redirect' => $user->role_name === 'Admin' ? url('/admin/dashboard') : url('/user/dashboard')
        ]);
    }
    

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    
        return response()->json([
            'status' => 'success',
            'message' => 'Anda berhasil logout!',
            'redirect' => route('login') // Otomatis ambil route login
        ]);
    }
    
}
