<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\UserRequest;

class UserController extends Controller
{
    public function showRegisterForm()
    {
        return view('register');
    }
       
    public function createUser(CreateRequest $request)
    {
        try {
            User::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_name' => 'User',
                'jurusan' => $request->jurusan,
                'no_hp' => $request->no_hp,
                'address' => $request->address,
                'status' => 0,
            ]);
    
            return response()->json([
                'success' => true,
                'message' => 'Akun berhasil dibuat. Tunggu verifikasi Admin!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan. Mohon coba lagi.'
            ], 500);
        }
    }   
    public function index(){
        return view('dashboard');
    }
}
