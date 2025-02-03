<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Realrashid\Sweetalert\Alert;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function index()
{
    // Mengambil semua pengguna yang bukan admin
    $users = User::where('role_name', '!=', 'Admin')->get();
    $totalUsers = $users->count();

    // Mengambil total admin
    $totalAdmins = User::where('role_name', 'Admin')->count();

    return view('dashboard', compact('totalUsers', 'totalAdmins', 'users'));
}

    public function showUserDataTable(Request $request)
{
    // Pastikan hanya Admin yang bisa mengakses halaman ini
    if (auth()->user()->role_name !== 'Admin') {
        return redirect()->route('dashboard');  // Redirect ke dashboard jika bukan admin
    }

    // Filter berdasarkan pencarian (jika ada)
    $query = User::query();  // Menampilkan semua pengguna

    if ($request->has('search') && !empty($request->search)) {
        $query->where('username', 'LIKE', '%' . $request->search . '%');
    }

    $users = $query->paginate(10)->appends(['search' => $request->search]); // Menambahkan parameter search ke pagination

    return view('user-table', compact('users'));
}


    public function indek()
    {
        return view('dashboard');
    }

    public function update(Request $request, $id)
{
    // Validate incoming data
    $validatedData = $request->validate([
        'username' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users,email,' . $id,
        'no_hp' => 'required|numeric|digits_between:10,15|unique:users,no_hp,' . $id,
        'address' => 'nullable|string|max:255',
        'jurusan' => 'required|string|max:100',
        'status' => 'required|in:1,0',
    ]);

    try {
        // Find the user by ID
        $user = User::findOrFail($id);

        // Update user fields
        $user->username = $validatedData['username'];
        $user->email = $validatedData['email'];
        $user->no_hp = $validatedData['no_hp'];
        $user->address = $validatedData['address'] ?: $user->address; // Keep old address if not provided
        $user->jurusan = $validatedData['jurusan'];
        $user->status = $validatedData['status'];
        $user->save();

        // Redirect back with success message
        alert()->success('Update Successful', 'User  updated successfully.'); // Corrected alert message
        return redirect()->route('user-table')->with('success', 'User  updated successfully.');

    } catch (\Exception $e) {
        Log::error('Error updating user: ' . $e->getMessage());
        return redirect()->route('user-table')->with('error', 'An error occurred while updating the user.');
    }
}
public function showLoginForm()
    {
        return view('login'); // Mengarah ke file blade login
    }
    public function login(Request $request)
    {
        // Validate the input data
        $credentials = $request->validate([
            'email' => 'required|email|exists:users,email', // email must exist in the database
            'password' => 'required|min:6', // password must be at least 6 characters
        ]);

        // Attempt login
        if (Auth::attempt($credentials, $request->remember)) {
            $user = Auth::user();

            // Check if the user's account is active
            if ($user->status == 0) {
                Auth::logout(); 
                alert()->error('Login Failed', 'Akun Anda belum aktif. Silakan hubungi Admin.');

                return back()->withErrors([
                    'email' => 'Akun Anda belum aktif. Silakan hubungi Admin.',
                ]);
            }

            // Regenerate the session to avoid session fixation attacks
            $request->session()->regenerate();

            alert()->success('Login Successful', 'Welcome, ' . $user->username);

            // Redirect to dashboard based on role
            if ($user->role_name === 'Admin') {
                return redirect()->intended('/admin/dashboard');
            } else {
                return redirect()->intended('/user/dashboard');
            }
        }

        // If login fails
        alert()->error('Login Failed', 'The provided credentials do not match our records.');

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
            // Hanya validasi field yang diisi
            $rules = [];

            // Validasi username jika diisi
            if ($request->has('username') && !empty($request->username)) {
                $rules['username'] = 'required|string|max:255|unique:users,username';
            }

            // Validasi email jika diisi
            if ($request->has('email') && !empty($request->email)) {
                $rules['email'] = 'required|email|unique:users,email|max:255';
            }

            // Validasi nomor telepon jika diisi
            if ($request->has('no_hp') && !empty($request->no_hp)) {
                $rules['no_hp'] = 'required|numeric|unique:users,no_hp|digits_between:10,15';
            }

            // Validasi password jika diisi
            if ($request->has('password') && !empty($request->password)) {
                $rules['password'] = 'required|min:8|confirmed';
            }

            // Validasi lainnya
            if ($request->has('address') && !empty($request->address)) {
                $rules['address'] = 'nullable|string|max:255';
            }

            if ($request->has('jurusan') && !empty($request->jurusan)) {
                $rules['jurusan'] = 'required|string|max:100';
            }

            // Terapkan validasi dinamis berdasarkan input
            $validatedData = $request->validate($rules);

            // Buat user baru dengan status nonaktif
            User::create([
                'username' => $validatedData['username'] ?? null,
                'email' => $validatedData['email'] ?? null,
                'password' => isset($validatedData['password']) ? Hash::make($validatedData['password']) : null,
                'role_name' => 'User',
                'jurusan' => $validatedData['jurusan'] ?? null,
                'no_hp' => $validatedData['no_hp'] ?? null,
                'address' => $validatedData['address'] ?? null,
                'status' => 0, // Status nonaktif
            ]);

            alert()->success('Akun berhasil dibuat.', 'Akun Anda akan aktif setelah diverifikasi oleh Admin.');
            return redirect()->route('login');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Tangani validasi error
            $errors = $e->validator->errors()->all();
            alert()->error('Terjadi kesalahan validasi:', implode(' ', $errors));

            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            // Tangani kesalahan umum
            alert()->error('Terjadi kesalahan:', 'Mohon coba lagi nanti.');
            return back()->with('error', 'Terjadi kesalahan. Mohon coba lagi nanti.');
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
            // Validasi input
            $validatedData = $request->validate([
                'username' => 'required|string|max:255|unique:users,username',
                'email' => 'required|email|max:255|unique:users,email',
                'no_hp' => 'required|numeric|digits_between:10,15|unique:users,no_hp',
                'password' => 'required|min:8|confirmed',
                'address' => 'nullable|string|max:255',
                'jurusan' => 'required|string|max:100',
                'role_name' => 'required|in:Admin,User',
            ]);
    
            // Simpan user baru
            User::create([
                'username' => $validatedData['username'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'role_name' => $validatedData['role_name'],
                'jurusan' => $validatedData['jurusan'],
                'no_hp' => $validatedData['no_hp'],
                'address' => $validatedData['address'],
                'status' => 0, // Status default nonaktif
            ]);
    
            return response()->json(['success' => true, 'message' => 'User created successfully'], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Tangani error validasi
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            // Tangani error lainnya
            Log::error($e->getMessage());
            return response()->json(['message' => 'An error occurred'], 500);
        }
    }
    
    public function destroy($id)
    {
        
        try {
            $user = User::findOrFail($id);
    
            // Cek jika user adalah admin seeder, maka tidak bisa dihapus
            if ($user->is_seeder) {
                alert()->error('Gagal Menghapus', 'Admin seeder tidak dapat dihapus.');
                return redirect()->route('user-table');
            }
            
    
            $user->delete();
    
            alert()->success('User Dihapus', 'User berhasil dihapus.');
            return redirect()->route('user-table');
        } catch (Exception $e) {
            alert()->error('Gagal Menghapus', 'Terjadi kesalahan saat menghapus user.');
            return redirect()->route('user-table');
        }
    }
    
    

    
    
}