<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateRequest;
use App\Http\Requests\UserRequest;
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
    
        $users = User::when($request->search, function ($query) use ($request) {
            $query->where('username', 'LIKE', "%{$request->search}%")
                  ->orWhere('jurusan', 'LIKE', "%{$request->search}%")
                  ->orWhere('role_name', 'LIKE', "%{$request->search}%");
        })->paginate(10)->appends(['search' => $request->search]);
    
        return view('user-table', compact('users'));
    }

    public function store(UserRequest $request)
    {
        try {
            $validatedData = $request->validated();
            if (!isset($validatedData['password_confirmation'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Konfirmasi password wajib diisi!'
                ], 422);
            }
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

            return response()->json([
                'success' => true,
                'message' => 'User created successfully'
            ], 201);

        } catch (\Exception $e) {
            Log::error('Error creating user: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating user.'
            ], 500);
        }
    }            
    public function update(UpdateRequest $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->update($request->validated());
    
            return response()->json([
                'success' => true,
                'message' => 'User updated successfully',
                'redirect_url' => url('/admin/user-datatable')
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error updating user: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat memperbarui user.'], 500);
        }
    }
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            if ($user->is_seeder) {
                return response()->json(['status' => 'error', 'message' => 'Admin seeder tidak dapat dihapus.'], 403);
            }
            $user->delete();
            return response()->json(['status' => 'success', 'message' => 'User berhasil dihapus.']);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Terjadi kesalahan saat menghapus user.'], 500);
        }
    }
}