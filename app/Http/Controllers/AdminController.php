<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Realrashid\Sweetalert\Alert;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::where('role_name', '!=', 'Admin')->get();
        $totalUsers = $users->count();
    
        $totalAdmins = User::where('role_name', 'Admin')->count();
    
        return view('dashboard', compact('totalUsers', 'totalAdmins', 'users'));
    }

    public function showUserDataTable(Request $request)
    {
        if (auth()->user()->role_name !== 'Admin') {
            return redirect()->route('dashboard');  
        }
    
        $query = User::query();  
    
        if ($request->has('search') && !empty($request->search)) {
            $query->where('username', 'LIKE', '%' . $request->search . '%');
        }
    
        $users = $query->paginate(10)->appends(['search' => $request->search]); 
    
        return view('user-table', compact('users'));
    }


    public function indek()
    {
        return view('dashboard');
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'no_hp' => 'required|numeric|digits_between:10,15|unique:users,no_hp,' . $id,
            'address' => 'nullable|string|max:255',
            'jurusan' => 'required|string',
            'status' => 'required|in:1,0',
        ]);
    
        try {
            $user = User::findOrFail($id);
            $user->update($validatedData);
    
            return response()->json(['success' => 'User updated successfully'], 200);
        } catch (\Exception $e) {
            Log::error('Error updating user: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat memperbarui user.'], 500);
        }
    }
    
    public function showLoginForm()
    {
        return view('login'); 
    }
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email|exists:users,email', 
            'password' => 'required|min:6', 
        ]);
        if (Auth::attempt($credentials, $request->remember)) {
            $user = Auth::user();

            if ($user->status == 0) {
                Auth::logout(); 
                // alert()->error('Login Failed', 'Akun Anda belum aktif. Silakan hubungi Admin.');

                return back()->withErrors([
                    'email' => 'Akun Anda belum aktif. Silakan hubungi Admin.',
                ]);
            }

            $request->session()->regenerate();
            alert()->success('Login Successful', 'Welcome, ' . $user->username);
            if ($user->role_name === 'Admin') {
                return redirect()->intended('/admin/dashboard');
            } else {
                return redirect()->intended('/user/dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }


    public function showRegisterForm()
    {
        return view('register');
    }
         
    public function createUser(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'username' => 'required|string|max:255|unique:users,username',
                'email' => 'required|email|max:255|unique:users,email',
                'no_hp' => 'required|numeric|digits_between:10,15|unique:users,no_hp',
                'password' => 'required|min:8|confirmed',
                'address' => 'nullable|string|max:255',
                'jurusan' => 'required|string|max:100',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
    
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
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        alert()->success('Logged Out', 'You have been successfully logged out.');

        return redirect('/login');
    }
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'username' => 'required|string|max:255|unique:users,username',
                'email' => 'required|email|max:255|unique:users,email',
                'no_hp' => 'required|numeric|digits_between:10,15|unique:users,no_hp',
                'password' => 'required|min:8|confirmed',
                'address' => 'nullable|string|max:255',
                'jurusan' => 'required|string|max:100',
                'role_name' => 'required|in:Admin,User',
            ]);
            User::create([
                'username' => $validatedData['username'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'role_name' => $validatedData['role_name'],
                'jurusan' => $validatedData['jurusan'],
                'no_hp' => $validatedData['no_hp'],
                'address' => $validatedData['address'],
                'status' => 0, 
            ]);
    
            return response()->json(['success' => true, 'message' => 'User created successfully'], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => 'An error occurred'], 500);
        }
    }
    
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            if ($user->is_seeder) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Admin seeder tidak dapat dihapus.'
                ], 403);
            }
    
            $user->delete();
    
            return response()->json([
                'status' => 'success',
                'message' => 'User berhasil dihapus.'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menghapus user.'
            ], 500);
        }
    }   
}