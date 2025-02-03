    <?php

    use Illuminate\Support\Facades\Route;
    use Illuminate\Support\Facades\Auth;
    use App\Http\Controllers\AdminController;
    use App\Http\Controllers\UserController;

    /*
    |-------------------------------------------------------------------------- 
    | Web Routes 
    |-------------------------------------------------------------------------- 
    | 
    | Here is where you can register web routes for your application. These 
    | routes are loaded by the RouteServiceProvider and all of them will 
    | be assigned to the "web" middleware group. Make something great! 
    |
    */

    // Halaman utama, redirect ke halaman login
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    // Rute untuk login, logout, dan registrasi
    Route::get('/login', [AdminController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminController::class, 'login']);
    Route::get('/register', [AdminController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AdminController::class, 'createUser'])->name('register.submit');
    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');

    // Rute untuk pengguna yang sudah login
    Route::middleware('auth')->group(function () {

        // Redirect dashboard berdasarkan peran
        Route::get('/dashboard', function () {
            return Auth::user()->role_name === 'Admin' 
                ? redirect()->route('admin.dashboard') 
                : redirect()->route('user.dashboard');
        })->name('dashboard');

        // Rute dengan prefix "admin" untuk Admin
        Route::prefix('admin')->middleware('role:Admin')->group(function () {
            Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

            // Rute untuk pengelolaan pengguna
            // Route::resource('users', AdminController::class)->except(['edit', 'create']);
            Route::put('/users/update/{id}', [AdminController::class, 'update'])->name('users.update');

            Route::get('/user-datatable', [AdminController::class, 'showUserDataTable'])->name('user-table');
            Route::get('/users/datatables', [AdminController::class, 'getUsersDatatables'])->name('users.datatables');
            Route::post('/users/store', [AdminController::class, 'store'])->name('users.store');
            Route::delete('/users/{id}', [AdminController::class, 'destroy'])->name('users.destroy');


        });

        // Rute dengan prefix "user" untuk User
        Route::prefix('user')->middleware('role:User')->group(function () {
            Route::get('/dashboard', [AdminController::class, 'indek'])->name('user.dashboard');
        });
    });