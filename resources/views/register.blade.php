<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        animation: slideIn 1s ease-out;
    }

    .auth-container h3 {
        text-align: center;
        color: #007bff;
        font-weight: 600;
    }

    .error-message {
        font-size: 12px;
        color: #dc3545;
        margin-top: 5px;
    }

    .form-floating {
        position: relative;
    }

    .toggle-password {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #6c757d;
    }

    .toggle-password:hover {
        color: #007bff;
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
    </style>
</head>

<body>
    <div class="auth-container">
        <h3><i class="fas fa-user-plus"></i> Register</h3>
        <form id="registerForm">
            @csrf

            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="username" name="username" placeholder="Username">
                <label for="username"><i class="fas fa-user"></i> Username</label>
                <div class="error-message" id="error-username"></div>
            </div>

            <div class="form-floating mb-3">
                <input type="email" class="form-control" id="email" name="email" placeholder="Email">
                <label for="email"><i class="fas fa-envelope"></i> Email</label>
                <div class="error-message" id="error-email"></div>
            </div>

            <div class="form-floating mb-3">
                <input type="tel" class="form-control" id="no_hp" name="no_hp" placeholder="Phone Number">
                <label for="no_hp"><i class="fas fa-phone"></i> Phone Number</label>
                <div class="error-message" id="error-no_hp"></div>
            </div>

            <div class="mb-3">
                <label for="jurusan"><i class="fas fa-graduation-cap"></i> Jurusan</label>
                <select class="form-control" id="jurusan" name="jurusan">
                    <option value="" selected disabled>-- Pilih Jurusan --</option>
                    <option value="Teknik Informatika">Teknik Informatika</option>
                    <option value="Sistem Informasi">Sistem Informasi</option>
                    <option value="Manajemen">Manajemen</option>
                    <option value="Akuntansi">Akuntansi</option>
                </select>
                <div class="error-message" id="error-jurusan"></div>
            </div>

            <div class="mb-3">
                <label for="address"><i class="fas fa-map-marker-alt"></i> Address</label>
                <textarea class="form-control" id="address" name="address" rows="3"
                    placeholder="Enter your address"></textarea>
                <div class="error-message" id="error-address"></div>
            </div>

            <div class="form-floating mb-3 position-relative">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                <label for="password"><i class="fas fa-lock"></i> Password</label>
                <span class="toggle-password" toggle="#password"><i class="fas fa-eye"></i></span>
                <div class="error-message" id="error-password"></div>
            </div>

            <div class="form-floating mb-3 position-relative">
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                    placeholder="Confirm Password">
                <label for="password_confirmation"><i class="fas fa-lock"></i> Confirm Password</label>
                <span class="toggle-password" toggle="#password_confirmation"><i class="fas fa-eye"></i></span>
                <div class="error-message" id="error-password_confirmation"></div>
            </div>

            <button type="submit" id="registerButton" class="btn btn-primary w-100">Register</button>

            <div class="text-center mt-3">
                <a href="{{ route('login') }}">Already have an account? Login</a>
            </div>
        </form>
    </div>

    <script>
    $(document).ready(function() {
        $(".toggle-password").click(function() {
            let input = $($(this).attr("toggle"));
            let icon = $(this).find("i");

            if (input.attr("type") === "password") {
                input.attr("type", "text");
                icon.removeClass("fa-eye").addClass("fa-eye-slash");
            } else {
                input.attr("type", "password");
                icon.removeClass("fa-eye-slash").addClass("fa-eye");
            }
        });

        $("#no_hp").on("input", function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        // ✅ Hapus error saat mulai mengetik, tapi tidak reset nilai input
        $("input, textarea, select").on("input change", function() {
            $(this).removeClass("is-invalid");
            $("#error-" + $(this).attr("name")).text("");
        });

        $("#registerForm").submit(function(e) {
            e.preventDefault();
            let formData = $(this).serialize();

            $.ajax({
                url: "{{ route('register.submit') }}",
                type: "POST",
                data: formData,
                dataType: "json",

                // ✅ Tampilkan loading dengan SweetAlert sebelum AJAX dikirim
                beforeSend: function() {
                    Swal.fire({
                        title: "Mendaftarkan akun...",
                        text: "Mohon tunggu sebentar...",
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading(),
                        backdrop: "rgba(0,0,0,0.6)",
                    });
                },

                success: function(response) {
                    Swal.fire({
                        icon: "success",
                        title: "Berhasil!",
                        text: response.message,
                        showConfirmButton: false,
                        timer: 2000
                    });

                    setTimeout(() => {
                        window.location.href = "{{ route('login') }}";
                    }, 2000);
                },

                error: function(xhr) {
                    Swal.close(); // ✅ Tutup loading saat ada error

                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        $(".error-message").text("");
                        $(".is-invalid").removeClass("is-invalid");

                        $.each(errors, function(key, messages) {
                            $("#error-" + key).text(messages[0]);
                            $(`[name="${key}"]`).addClass("is-invalid");
                        });
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Terjadi Kesalahan",
                            text: "Mohon coba lagi nanti."
                        });
                    }
                }
            });
        });

    });
    </script>
</body>

</html>