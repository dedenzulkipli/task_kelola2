@extends('template.layout')

@section('title', 'User DataTable')

@section('content')
<div class="container mt-4">
    <h2 class="text-center mb-4">User Management</h2>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i> User DataTable
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <!-- Form Pencarian -->
                <div class="col-md-6">
                    <form id="searchForm" action="{{ route('user-table') }}" method="GET" class="d-flex">
                        <input type="text" class="form-control" placeholder="Search by name" name="search"
                            value="{{ request()->search }}" id="searchInput" />
                        <button type="submit" class="btn btn-primary ms-2">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>

                <!-- Tombol Create User -->
                <div class="col-md-6 text-end">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                        <i class="fas fa-plus"></i> Create User
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table id="userTable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>No HP</th>
                            <th>Address</th>
                            <th>Jurusan</th>
                            <th>Status</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->no_hp }}</td>
                            <td>{{ $user->address }}</td>
                            <td>{{ $user->jurusan }}</td>
                            <td>
                                @if($user->status == 1)
                                <span class="badge bg-success">Active</span>
                                @else
                                <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                            <td>{{ $user->role_name }}</td>
                            <td>
                                @if(!$user->is_seeder)
                                <button class="btn btn-primary btn-sm editUserBtn" data-id="{{ $user->id }}"
                                    data-username="{{ $user->username }}" data-email="{{ $user->email }}"
                                    data-no_hp="{{ $user->no_hp }}" data-address="{{ $user->address }}"
                                    data-jurusan="{{ $user->jurusan }}" data-status="{{ $user->status }}">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <button type="button" class="btn btn-danger btn-sm"
                                    onclick="confirmDelete('{{ $user->id }} + {{ $user->username }}')">
                                    <i class="fas fa-trash-alt"></i>
                                </button>



                                @else
                                <span class="text-muted">Admin Seeder</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <!-- Modal Edit Global -->
                    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title"><i class="fas fa-edit"></i> Edit User</h5>
                                    <button type="button" class="btn-close btn-close-white"
                                        data-bs-dismiss="modal"></button>
                                </div>
                                <form id="editUserForm">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="id" id="userId">
                                    <div class="modal-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Username</label>
                                                <input type="text" class="form-control" id="username" name="username">
                                                <div class="invalid-feedback"></div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Email</label>
                                                <input type="email" class="form-control" id="email" name="email">
                                                <div class="invalid-feedback"></div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">No HP</label>
                                                <input type="tel" class="form-control" id="no_hp" name="no_hp">
                                                <div class="invalid-feedback"></div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Address</label>
                                                <textarea class="form-control" id="address" name="address"></textarea>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Jurusan</label>
                                                <select class="form-control" id="jurusan" name="jurusan">
                                                    <option value="Teknik Informatika">Teknik Informatika</option>
                                                    <option value="Sistem Informasi">Sistem Informasi</option>
                                                    <option value="Manajemen">Manajemen</option>
                                                    <option value="Akuntansi">Akuntansi</option>
                                                </select>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Status</label>
                                                <select class="form-control" id="status" name="status">
                                                    <option value="1">Active</option>
                                                    <option value="0">Inactive</option>
                                                </select>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                            <i class="fas fa-times"></i> Close
                                        </button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Save changes
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </table>
                <!-- Modal -->
                <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('users.store') }}" method="POST" id="createUserForm">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title" id="createModalLabel">Create User</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <!-- Username -->
                                    <div class="form-group mb-3">
                                        <label for="username">Username</label>
                                        <input type="text" class="form-control" id="username" name="username">
                                        <span class="invalid-feedback"></span>
                                    </div>

                                    <!-- Email -->
                                    <div class="form-group mb-3">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" id="email" name="email">
                                        <span class="invalid-feedback"></span>
                                    </div>

                                    <!-- No HP -->
                                    <div class="form-group mb-3">
                                        <label for="no_hp">No HP</label>
                                        <input type="text" class="form-control" id="no_hp" name="no_hp">
                                        <span class="invalid-feedback"></span>
                                    </div>

                                    <!-- Password -->
                                    <div class="form-group mb-3 position-relative">
                                        <label for="password3">Password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="password3" name="password">
                                            <button type="button" class="btn btn-outline-secondary toggle-password">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                        <span class="invalid-feedback"></span>
                                    </div>

                                    <!-- Confirm Password -->
                                    <div class="form-group mb-3 position-relative">
                                        <label for="password_confirmation2">Confirm Password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="password_confirmation2"
                                                name="password_confirmation">
                                            <button type="button" class="btn btn-outline-secondary toggle-password">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                        <span class="invalid-feedback"></span>
                                    </div>

                                    <select class="form-control" id="jurusan" name="jurusan">
                                        <option value="" selected disabled>-- Pilih Jurusan --</option>
                                        <option value="Teknik Informatika">Teknik Informatika</option>
                                        <option value="Sistem Informasi">Sistem Informasi</option>
                                        <option value="Manajemen">Manajemen</option>
                                        <option value="Akuntansi">Akuntansi</option>
                                        <span class="invalid-feedback"></span>
                                    </select>
                                    <div class="form-group mb-3">
                                        <label for="address">Address</label>
                                        <textarea class="form-control" id="address" name="address"></textarea>
                                        <span class="invalid-feedback"></span>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="role_name">Role</label>
                                        <select class="form-control" id="role_name" name="role_name">
                                            <option value="User">User</option>
                                            <option value="Admin">Admin</option>
                                        </select>
                                        <span class="invalid-feedback"></span>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Create</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-center mt-4">
                    <ul class="pagination pagination-sm">
                        {{ $users->links('pagination::bootstrap-4') }}
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@include('sweetalert::alert')

@endsection

@section('scripts')
<script>
// Submit form pencarian otomatis
document.getElementById('searchInput').addEventListener('input', function() {
    document.getElementById('searchForm').submit();
});

// Batasi input No HP agar hanya angka
document.querySelectorAll('input[name="no_hp"]').forEach(function(input) {
    input.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
});

$(document).ready(function() {
    $('#createUserForm').on('submit', function(e) {
        e.preventDefault(); // Mencegah form submit default

        let form = $(this);
        let formData = form.serialize(); // Ambil data form

        $.ajax({
            url: form.attr("action"), // Ambil URL dari atribut action form
            type: "POST",
            data: formData,
            dataType: "json",
            beforeSend: function() {
                $('.invalid-feedback').text("").hide(); // Reset pesan error
                $('.is-invalid').removeClass('is-invalid');
                $('#createUserForm button[type="submit"]').prop('disabled',
                    true); // Disable tombol
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: "success",
                        title: "Success",
                        text: response.message,
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 3000
                    });

                    $('#createModal').modal('hide'); // Tutup modal
                    form.trigger("reset"); // Reset form
                    location.reload(); // Refresh halaman
                }
            },
            error: function(xhr) {
                console.log("Error Response:", xhr.responseJSON); // Debug error di console

                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;

                    $('.invalid-feedback').text("").hide(); // Reset pesan error
                    $('.is-invalid').removeClass('is-invalid'); // Hapus tanda error lama

                    $.each(errors, function(key, value) {
                        let input = $('[name="' + key + '"]');
                        input.addClass('is-invalid'); // Tambahkan class error
                        input.next('.invalid-feedback').text(value[0])
                    .show(); // Tampilkan error
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: xhr.responseJSON.message || "Terjadi kesalahan.",
                    });
                }
            },

            complete: function() {
                $('#createUserForm button[type="submit"]').prop('disabled',
                    false); // Aktifkan kembali tombol submit
            }
        });
    });

    // Toggle password visibility
    $(".toggle-password").click(function() {
        let input = $(this).prev("input");
        let icon = $(this).find("i");

        if (input.attr("type") === "password") {
            input.attr("type", "text");
            icon.removeClass("fa-eye").addClass("fa-eye-slash");
        } else {
            input.attr("type", "password");
            icon.removeClass("fa-eye-slash").addClass("fa-eye");
        }
    });

    // Reset form saat modal ditutup
    $('#createModal').on('hidden.bs.modal', function() {
        $('#createUserForm').trigger("reset");
        $('#jurusan').val('');
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text("").hide();
    });
});



//editt modal
$(document).ready(function() {
    $('.editUserBtn').on('click', function() {
        let userId = $(this).data('id');
        let username = $(this).data('username');
        let email = $(this).data('email');
        let no_hp = $(this).data('no_hp');
        let address = $(this).data('address');
        let jurusan = $(this).data('jurusan');
        let status = $(this).data('status');

        $('#userId').val(userId);
        $('#username').val(username);
        $('#email').val(email);
        $('#no_hp').val(no_hp);
        $('#address').val(address);
        $('#jurusan').val(jurusan);
        $('#status').val(status);

        $('#editUserForm').attr('action', '/admin/users/update/' + userId);

        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();

        $('#editModal').modal('show');
    });

    $('#editUserForm').on('submit', function(e) {
        e.preventDefault();

        let form = $(this);
        let actionUrl = form.attr('action');
        let formData = form.serialize() + '&_method=PUT';

        $.ajax({
            url: actionUrl,
            type: 'PUT',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                    'content')
            },
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'User updated successfully.',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });

                $('#editModal').modal('hide');
                setTimeout(() => {
                    window.location
                        .reload();
                }, 500);
            },
            error: function(xhr) {
                console.log("Error Response:", xhr.responseJSON);

                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;

                    $('.invalid-feedback').remove();
                    $('.is-invalid').removeClass('is-invalid');

                    $.each(errors, function(key, value) {
                        let input = $('[name="' + key +
                            '"]');
                        if (input.length > 0) {
                            input.addClass('is-invalid');
                            input.after('<div class="invalid-feedback">' + value[
                                0] + '</div>');
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON.message ||
                            'An unexpected error occurred.',
                    });
                }
            }
        });
    });
});

//delete
function confirmDelete(userId, username) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: `User "${username}" akan dihapus!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/admin/users/' + userId,
                type: 'POST',
                data: {
                    _method: 'DELETE',
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'User berhasil dihapus.',
                        showConfirmButton: false,
                        timer: 2000
                    });
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                },
                error: function(xhr) {
                    let message = xhr.responseJSON?.message || 'Terjadi kesalahan!';
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: message
                    });
                }
            });
        }
    });
}
</script>
@endsection