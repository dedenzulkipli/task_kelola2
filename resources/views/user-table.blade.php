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
                    <!-- Modal Edit -->
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
                                                <input type="text" class="form-control" id="edit_username"
                                                    name="username">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Email</label>
                                                <input type="email" class="form-control" id="edit_email" name="email">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">No HP</label>
                                                <input type="tel" class="form-control" id="edit_no_hp" name="no_hp">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Address</label>
                                                <textarea class="form-control" id="edit_address"
                                                    name="address"></textarea>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Jurusan</label>
                                                <select class="form-control" id="edit_jurusan" name="jurusan">
                                                    <option value="Teknik Informatika">Teknik Informatika</option>
                                                    <option value="Sistem Informasi">Sistem Informasi</option>
                                                    <option value="Manajemen">Manajemen</option>
                                                    <option value="Akuntansi">Akuntansi</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Status</label>
                                                <select class="form-control" id="edit_status" name="status">
                                                    <option value="1">Active</option>
                                                    <option value="0">Inactive</option>
                                                </select>
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
                <!-- Modal create -->
                <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <form action="{{ route('users.store') }}" method="POST" id="createUserForm">
                                @csrf
                                <div class="modal-header bg-success text-white">
                                    <h5 class="modal-title"><i class="fas fa-user-plus"></i> Create User</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="createUsername" class="form-label">Username</label>
                                            <input type="text" class="form-control" id="createUsername" name="username">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="createEmail" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="createEmail" name="email">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="createNoHp" class="form-label">No HP</label>
                                            <input type="text" class="form-control" id="createNoHp" name="no_hp">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="createJurusan" class="form-label">Jurusan</label>
                                            <select class="form-control" id="createJurusan" name="jurusan">
                                                <option value="" selected disabled>-- Pilih Jurusan --</option>
                                                <option value="Teknik Informatika">Teknik Informatika</option>
                                                <option value="Sistem Informasi">Sistem Informasi</option>
                                                <option value="Manajemen">Manajemen</option>
                                                <option value="Akuntansi">Akuntansi</option>
                                            </select>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="col-md-12">
                                            <label for="createAddress" class="form-label">Address</label>
                                            <textarea class="form-control" id="createAddress" name="address"
                                                rows="2"></textarea>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="createPassword" class="form-label">Password</label>
                                            <div class="input-group">
                                                <input type="password" class="form-control" id="createPassword"
                                                    name="password">
                                                <button type="button" class="btn btn-outline-secondary toggle-password">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="createPasswordConfirmation" class="form-label">Confirm
                                                Password</label>
                                            <div class="input-group">
                                                <input type="password" class="form-control"
                                                    id="createPasswordConfirmation" name="password_confirmation">
                                                <button type="button" class="btn btn-outline-secondary toggle-password">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="createRole" class="form-label">Role</label>
                                            <select class="form-control" id="createRole" name="role_name">
                                                <option value="User">User</option>
                                                <option value="Admin">Admin</option>
                                            </select>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                        <i class="fas fa-times"></i> Cancel
                                    </button>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save"></i> Create
                                    </button>
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
//ajax create
$(document).ready(function() {
    $('#createUserForm').on('submit', function(e) {
        e.preventDefault();

        let form = $(this);
        let formData = form.serialize(); 

        $.ajax({
            url: form.attr("action"),
            type: "POST",
            data: formData,
            dataType: "json",
            beforeSend: function() {
                $('.invalid-feedback').text("").hide(); 
                $('.is-invalid').removeClass('is-invalid');
                $('#createUserForm button[type="submit"]').prop('disabled', true);
            },
            success: function(response) {
                Swal.fire({
                    title: 'Processing...',
                    text: 'Creating user...',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                setTimeout(() => {
                    Swal.fire({
                        icon: "success",
                        title: response.message,
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true
                    });

                    $('#createModal').modal('hide'); 
                    $('#createUserForm').trigger("reset"); 

                    setTimeout(() => {
                        location.reload(); 
                    }, 500);
                }, 1200);
            },

            error: function(xhr) {
                console.error("Error Response:", xhr
                    .responseJSON); 

                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;

                    $('.invalid-feedback').text("").hide(); 
                    $('.is-invalid').removeClass('is-invalid');

                    $.each(errors, function(key, value) {
                        let input = $('[name="' + key + '"]');
                        input.addClass('is-invalid'); 
                        input.closest('.col-md-6, .col-md-12').find(
                                '.invalid-feedback')
                            .text(value[0]).show(); 
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
                $('#createUserForm button[type="submit"]').prop('disabled', false);
            }
        });
    });


    $(".toggle-password").click(function() {
        let input = $(this).prev("input");
        let icon = $(this).find("i");

        input.attr("type", input.attr("type") === "password" ? "text" : "password");
        icon.toggleClass("fa-eye fa-eye-slash");
    });

    $('#createModal').on('hidden.bs.modal', function() {
        $('#createUserForm').trigger("reset");
        $('#createJurusan').val(''); 
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text("").hide();
    });
});

//ajax edit
$('.editUserBtn').on('click', function() {
    let userId = $(this).data('id');

    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').remove();


    let username = $(this).data('username') || '';
    let email = $(this).data('email') || '';
    let no_hp = $(this).data('no_hp') || '';
    let address = $(this).data('address') || '';
    let jurusan = $(this).data('jurusan') || '';
    let status = $(this).data('status');


    $('#userId').val(userId);
    $('#edit_username').val(username);
    $('#edit_email').val(email);
    $('#edit_no_hp').val(no_hp);
    $('#edit_address').val(address);
    $('#edit_jurusan').val(jurusan);


    if (status !== undefined && status !== null) {
        $('#edit_status').val(status.toString()).change();
    }


    $('#editUserForm').attr('action', `/admin/users/update/${userId}`);

    $('#editModal').modal('show');
});

    $('#editUserForm').on('submit', function(e) {
        e.preventDefault();

        let form = $(this);
        let actionUrl = form.attr('action');
        let formData = form.serialize();
        let submitButton = form.find('button[type="submit"]');

        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();

 
        Swal.fire({
            title: 'Processing...',
            text: 'Updating user...',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        submitButton.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');

        $.ajax({
            url: actionUrl,
            type: 'PUT',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                Swal.fire({
                    icon: "success",
                    title: response.message || "User updated successfully!",
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 1500,
                    timerProgressBar: true
                });

                $('#editModal').modal('hide');

                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            },
            error: function(xhr) {
                Swal.close(); 
                submitButton.prop('disabled', false).html(
                    '<i class="fas fa-save"></i> Save changes');

                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        let input = $('#edit_' + key);
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
            },
            complete: function() {
                submitButton.prop('disabled', false).html(
                    '<i class="fas fa-save"></i> Save changes');
            }
        });
    });

    $('#editModal').on('hidden.bs.modal', function() {
        $('#editUserForm')[0].reset();
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();
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
            Swal.fire({
                title: 'Menghapus...',
                text: 'Mohon tunggu sebentar.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading(); 
                }
            });

            $.ajax({
                url: `/admin/users/${userId}`,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 2000
                    });
                    setTimeout(() => window.location.reload(), 1500);
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