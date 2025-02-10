<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
        padding: 60px 50px;
        width: 100%;
        max-width: 450px;
        animation: slideIn 1s ease-out;
    }

    .auth-container h3 {
        text-align: center;
        margin-bottom: 30px;
        color: #007bff;
        font-weight: 600;
    }

    .form-floating input,
    .form-floating label {
        font-size: 16px;
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

    .is-invalid {
        border-color: #dc3545;
        /* Red border when error occurs */
    }

    .invalid-feedback {
        display: block;
        color: #dc3545;
        /* Red text for error messages */
        font-size: 14px;
    }
    </style>
</head>

<body>
    <div class="auth-container">
        <h3><i class="fas fa-user-circle"></i> Login</h3>

        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <form id="loginForm">
            @csrf
            <div class="form-floating mb-3">
                <input type="email" class="form-control" id="email" name="email" placeholder="Email">
                <label for="email"><i class="fas fa-envelope"></i> Email</label>
                <div class="invalid-feedback" id="emailError"></div>
            </div>
            <div class="form-floating mb-3 position-relative">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                <label for="password"><i class="fas fa-lock"></i> Password</label>
                <span class="position-absolute top-50 end-0 translate-middle-y me-3 toggle-icon" id="togglePassword">
                    <i class="fas fa-eye"></i>
                </span>
                <div class="invalid-feedback" id="passwordError"></div>
            </div>

            <button type="submit" class="btn btn-primary w-100">Login</button>
            <div class="text-center mt-3">
                <a href="{{ route('register') }}">Don't have an account? Register</a>
            </div>
        </form>

    </div>

    <!-- @include('sweetalert::alert')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    // Toggle Password Visibility
    document.addEventListener('DOMContentLoaded', function() {
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
    });
    $(document).ready(function() {
        $("#loginForm").on("submit", function(e) {
            e.preventDefault(); // Mencegah reload halaman

            // Reset error messages
            $(".invalid-feedback").text("");
            $(".form-control").removeClass("is-invalid");

            $.ajax({
                url: "{{ route('login') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    email: $("#email").val(),
                    password: $("#password").val(),
                },
                beforeSend: function() {
                    Swal.fire({
                        title: "Logging in...",
                        text: "Please wait...",
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });
                },
                success: function(response) {
                    Swal.fire({
                        icon: "success",
                        title: "Login Berhasil!",
                        text: "Selamat datang, " + response.username,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = response
                        .redirect; // Redirect ke halaman dashboard
                    });
                },
                error: function(xhr) {
                    Swal.close(); // Tutup loading

                    if (xhr.status === 422) { // Validasi error
                        let errors = xhr.responseJSON.errors;
                        if (errors.email) {
                            $("#email").addClass("is-invalid");
                            $("#emailError").text(errors.email[0]);
                        }
                        if (errors.password) {
                            $("#password").addClass("is-invalid");
                            $("#passwordError").text(errors.password[0]);
                        }
                    } else { // Jika email/password salah
                        Swal.fire({
                            icon: "error",
                            title: "Login Gagal!",
                            text: xhr.responseJSON.message ||
                                "Cek kembali email & password Anda"
                        });
                    }
                }
            });
        });
    });
    document.addEventListener("DOMContentLoaded", function() {
    let logoutMessage = "{{ session('logout_success') }}";
    if (logoutMessage) {
        Swal.fire({
            icon: "success",
            title: "Logged Out",
            text: logoutMessage,
            timer: 2000,
            showConfirmButton: false
        });
    }
});
</script>
</body>
</html>