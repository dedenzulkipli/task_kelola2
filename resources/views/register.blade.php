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
        font-size: 18px;
    }

    .error-message {
        font-size: 12px;
        color: #dc3545;
        margin-bottom: 5px;
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

    .form-floating {
        position: relative;
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
                <label for="address"><i class="fas fa-map-marker-alt"></i> Address</label>
                <textarea class="form-control" id="address" name="address" rows="3"
                    placeholder="Enter your address"></textarea>
                <div class="error-message" id="error-address"></div>
            </div>

            <div class="mb-3">
                <label for="jurusan"><i class="fas fa-graduation-cap"></i> Jurusan</label>
                <select class="form-control" id="jurusan" name="jurusan">
                    <!-- <option value="" selected disabled>-- Pilih Jurusan --</option> -->
                    <option value="Teknik Informatika">Teknik Informatika</option>
                    <option value="Sistem Informasi">Sistem Informasi</option>
                    <option value="Manajemen">Manajemen</option>
                    <option value="Akuntansi">Akuntansi</option>
                </select>
                <div class="error-message" id="error-jurusan"></div>
            </div>

            <div class="form-floating mb-3">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                <label for="password"><i class="fas fa-lock"></i> Password</label>
                <div class="error-message" id="error-password"></div>
                <span class="toggle-password" toggle="#password"><i class="fas fa-eye"></i></span>
            </div>

            <div class="form-floating mb-3">
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                    placeholder="Confirm Password">
                <label for="password_confirmation"><i class="fas fa-lock"></i> Confirm Password</label>
                <div class="error-message" id="error-password_confirmation"></div>
                <span class="toggle-password" toggle="#password_confirmation"><i class="fas fa-eye"></i></span>
            </div>


            <button type="submit" id="registerButton" class="btn btn-primary w-100">Register</button>

            <div class="text-center mt-3">
                <a href="{{ route('login') }}">Already have an account? Login</a>
            </div>
        </form>
    </div>
    <!--  -->
    <script>
    document.getElementById('no_hp').addEventListener('input', function(e) {
        // Hanya izinkan angka
        this.value = this.value.replace(/[^0-9]/g, '');
    });
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

    $("input, select, textarea").on("input change", function() {
        validateField($(this));
    });

    $("#registerForm").submit(function(e) {
        e.preventDefault();
        $(".error-message").text("");

        let isValid = true;
        $("input, select, textarea").each(function() {
            if (!validateField($(this))) {
                isValid = false;
            }
        });

        if (!isValid) return; // Stop jika ada error, tanpa proses loading

        let formData = $(this).serialize();

        $.ajax({
            url: "{{ route('register.submit') }}",
            type: "POST",
            data: formData,
            dataType: "json",
            beforeSend: function() {
                $("#registerButton").prop("disabled", true).text("Processing...");
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        toast: true,
                        position: "top-end",
                        icon: "success",
                        title: response.message,
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true
                    });

                    setTimeout(() => {
                        window.location.href = "{{ route('login') }}";
                    }, 2000);
                }
            },
            error: function(xhr) {
                $("#registerButton").prop("disabled", false).text("Register");

                if (xhr.status === 422) { 
                    let errors = xhr.responseJSON.errors;
                    $(".error-message").text(""); // Reset pesan error lama

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

    function validateField(input) {
        let value = input.val().trim();
        let fieldName = input.attr("name");
        let errorElement = $("#error-" + fieldName);
        let isValid = true;

        if (value === "") {
            errorElement.text("Field ini wajib diisi!");
            input.addClass("is-invalid");
            isValid = false;
        } else {
            errorElement.text("");
            input.removeClass("is-invalid");
        }

        if (fieldName === "email" && value !== "" && !validateEmail(value)) {
            errorElement.text("Format email tidak valid!");
            input.addClass("is-invalid");
            isValid = false;
        }

        if (fieldName === "no_hp" && value !== "" && !/^\d+$/.test(value)) {
            errorElement.text("Nomor HP hanya boleh berisi angka!");
            input.addClass("is-invalid");
            isValid = false;
        }

        if (fieldName === "password" && value.length < 8) {
            errorElement.text("Password minimal 8 karakter!");
            input.addClass("is-invalid");
            isValid = false;
        }

        return isValid;
    }

    function validateEmail(email) {
        let regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    }
});

</script>
</body>
</html>