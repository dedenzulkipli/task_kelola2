<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
    body {
        background: linear-gradient(to right, #007bff, #00c6ff);
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .auth-container {
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        padding: 30px;
        width: 600px;
        height: auto;
        animation: slideIn 1s ease-out;
    }

    .auth-container h3 {
        text-align: center;
        margin-bottom: 20px;
        color: #007bff;
        font-weight: 600;
        font-size: 18px;
    }

    .form-floating input,
    .form-floating label {
        font-size: 14px;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
        transition: background-color 0.3s, border-color 0.3s;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #004085;
    }

    .form-check-label {
        font-size: 14px;
    }

    .auth-container a {
        color: #007bff;
        font-size: 14px;
        text-decoration: none;
        transition: color 0.3s;
    }

    .auth-container a:hover {
        color: #0056b3;
    }

    .auth-container .alert {
        font-size: 14px;
        margin-bottom: 10px;
    }

    @keyframes slideIn {
        from {
            transform: translateY(30px);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .eye-icon {
        position: absolute;
        top: 50%;
        right: 10px;
        transform: translateY(-50%);
        cursor: pointer;
        color: #007bff;
    }

    .password-container {
        position: relative;
    }

    .password-container input[type="password"] {
        padding-right: 30px;
    }

    .error-message {
        font-size: 12px;
        color: #dc3545;
        margin-bottom: 5px;
    }
    </style>
</head>

<body>
    <div class="auth-container">
        <h3><i class="fas fa-user-plus"></i> Register</h3>

        <!-- @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif -->

        <form method="POST" action="{{ route('register.submit') }}">
            @csrf

            <!-- Username -->
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="username" name="username" placeholder="Username"
                    value="{{ old('username') }}" required>
                <label for="username"><i class="fas fa-user"></i> Username</label>
                @if ($errors->has('username'))
                <div class="error-message">{{ $errors->first('username') }}</div>
                @endif
            </div>

            <!-- Email -->
            <div class="form-floating mb-3">
                <input type="email" class="form-control" id="email" name="email" placeholder="Email"
                    value="{{ old('email') }}" required>
                <label for="email"><i class="fas fa-envelope"></i> Email</label>
                @if ($errors->has('email'))
                <div class="error-message">{{ $errors->first('email') }}</div>
                @endif
            </div>

            <!-- Phone Number -->
            <div class="form-floating mb-3">
                <input type="tel" class="form-control" id="no_hp" name="no_hp" placeholder="Phone Number"
                    value="{{ old('no_hp') }}" required inputmode="numeric" pattern="[0-9]+"
                    title="Nomor telepon hanya boleh mengandung angka">
                <label for="no_hp"><i class="fas fa-phone"></i> Phone Number</label>
                @if ($errors->has('no_hp'))
                <div class="error-message">{{ $errors->first('no_hp') }}</div>
                @endif
            </div>

            <!-- Address -->
            <div class="mb-3">
                @if ($errors->has('address'))
                <div class="error-message">{{ $errors->first('address') }}</div>
                @endif
                <label for="address" class="form-label"><i class="fas fa-map-marker-alt"></i> Address</label>
                <textarea class="form-control" id="address" name="address" rows="3"
                    placeholder="Address (Optional)">{{ old('address') }}</textarea>
            </div>

            <!-- Jurusan -->
            <div class="mb-3">
                @if ($errors->has('jurusan'))
                <div class="error-message">{{ $errors->first('jurusan') }}</div>
                @endif
                <label for="jurusan" class="form-label"><i class="fas fa-graduation-cap"></i> Jurusan</label>
                <select class="form-control" id="jurusan" name="jurusan" required>
                    <option value="Teknik Informatika" {{ old('jurusan') == 'Teknik Informatika' ? 'selected' : '' }}>
                        Teknik Informatika</option>
                    <option value="Sistem Informasi" {{ old('jurusan') == 'Sistem Informasi' ? 'selected' : '' }}>Sistem
                        Informasi</option>
                    <option value="Manajemen" {{ old('jurusan') == 'Manajemen' ? 'selected' : '' }}>Manajemen</option>
                    <option value="Akuntansi" {{ old('jurusan') == 'Akuntansi' ? 'selected' : '' }}>Akuntansi</option>
                </select>
            </div>

            <!-- Password -->
            <div class="form-floating mb-3 password-container">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password"
                    required>
                <label for="password"><i class="fas fa-lock"></i> Password</label>
                <span class="eye-icon" onclick="togglePassword('password')"><i class="fas fa-eye"></i></span>
                @if ($errors->has('password'))
                <div class="error-message">{{ $errors->first('password') }}</div>
                @endif
            </div>

            <!-- Confirm Password -->
            <div class="form-floating mb-3 password-container">
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                    placeholder="Confirm Password" required>
                <label for="password_confirmation"><i class="fas fa-lock"></i> Confirm Password</label>
                <span class="eye-icon" onclick="togglePassword('password_confirmation')"><i
                        class="fas fa-eye"></i></span>
                @if ($errors->has('password_confirmation'))
                <div class="error-message">{{ $errors->first('password_confirmation') }}</div>
                @endif
            </div>

            <button type="submit" class="btn btn-primary w-100">Register</button>

            <div class="text-center mt-3">
                <a href="{{ route('login') }}">Already have an account? Login</a>
            </div>
        </form>
    </div>

    <script>
    document.getElementById('no_hp').addEventListener('input', function(e) {
        // Hanya izinkan angka
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    function togglePassword(id) {
        const input = document.getElementById(id);
        const icon = input.nextElementSibling.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }
    </script>

    @include('sweetalert::alert')
</body>

</html>