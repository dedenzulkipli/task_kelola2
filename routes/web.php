<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application.
| These routes are loaded by the RouteServiceProvider and all of them
| will be assigned to the "web" middleware group. Make something great!
|
*/

// Halaman utama, redirect ke halaman login
Route::get('/', function () {
    return redirect()->route('dashboard');
});
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [UserController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [UserController::class, 'createUser'])->name('register.submit');
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return Auth::user()->role_name === 'Admin' 
            ? redirect()->route('admin.dashboard') 
            : redirect()->route('user.dashboard');
    })->name('dashboard');
    Route::prefix('admin')->middleware('role:Admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::put('/users/update/{id}', [AdminController::class, 'update'])->name('users.update');
        Route::get('/user-datatable', [AdminController::class, 'showUserDataTable'])->name('user-table');
        Route::get('/users/datatables', [AdminController::class, 'getUsersDatatables'])->name('users.datatables');
        Route::post('/users/store', [AdminController::class, 'store'])->name('users.store');
        Route::delete('/users/{id}', [AdminController::class, 'destroy'])->name('users.destroy');
    });
    Route::prefix('user')->middleware('role:User')->group(function () {
        Route::get('/dashboard', [UserController::class, 'index'])->name('user.dashboard');
    });
});
