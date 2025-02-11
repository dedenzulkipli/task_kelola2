<?php

namespace App\Http\Controllers;

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

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6',
        ]);
    
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            $user = Auth::user();
    
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
    
        return response()->json(['message' => 'Email atau password salah!'], 401);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login')->with('logout_success', 'You have been successfully logged out.');
    }
}
