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
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#editModal{{ $user->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form method="POST" action="/admin/users/{{ $user->id }}"
                                    onsubmit="return confirmDelete('{{ $user->username }}')" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>


                                @else
                                <span class="text-muted">Admin Seeder</span>
                                @endif
                            </td>
                        </tr>

                        <!-- <div class="modal fade" id="confirmDeleteModal" tabindex="-1"
                            aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger text-white">
                                        <h5 class="modal-title" id="confirmDeleteModalLabel">Konfirmasi Hapus</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Apakah Anda yakin ingin menghapus <strong id="modalUserName"></strong>?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Batal</button>
                                        <form id="deleteForm" method="POST" action="/admin/users/{{ $user->id }}"
                                            style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                        <!-- Modal Edit -->
                        <div class="modal fade" id="editModal{{ $user->id }}" tabindex="-1"
                            aria-labelledby="editModalLabel{{ $user->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title" id="editModalLabel{{ $user->id }}">
                                            <i class="fas fa-edit"></i> Edit User
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <form id="editUser Form{{ $user->id }}" action="/admin/users/update/{{ $user->id }}"
                                        method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="id" value="{{ $user->id }}">
                                        <div class="modal-body">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label for="username{{ $user->id }}"
                                                        class="form-label">UserName</label>
                                                    <input type="text"
                                                        class="form-control @error('username') is-invalid @enderror"
                                                        id="username{{ $user->id }}" name="username"
                                                        value="{{ old('username', $user->username) }}" required>
                                                    @error('username')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="email{{ $user->id }}" class="form-label">Email</label>
                                                    <input type="email"
                                                        class="form-control @error('email') is-invalid @enderror"
                                                        id="email{{ $user->id }}" name="email"
                                                        value="{{ old('email', $user->email) }}" required>
                                                    @error('email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="no_hp{{ $user->id }}" class="form-label">No HP</label>
                                                    <input type="tel"
                                                        class="form-control @error('no_hp') is-invalid @enderror"
                                                        id="no_hp{{ $user->id }}" name="no_hp"
                                                        value="{{ old('no_hp', $user->no_hp) }}" required
                                                        pattern="[0-9]+" title="Nomor telepon hanya boleh angka">
                                                    @error('no_hp')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="address{{ $user->id }}"
                                                        class="form-label">Address</label>
                                                    <textarea
                                                        class="form-control @error('address') is-invalid @enderror"
                                                        id="address{{ $user->id }}" name="address"
                                                        required>{{ old('address', $user->address) }}</textarea>
                                                    @error('address')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="jurusan{{ $user->id }}"
                                                        class="form-label">Jurusan</label>
                                                    <select class="form-control @error('jurusan') is-invalid @enderror"
                                                        id="jurusan{{ $user->id }}" name="jurusan" required>
                                                        <option value="Teknik Informatika"
                                                            {{ old('jurusan', $user->jurusan) == 'Teknik Informatika' ? 'selected' : '' }}>
                                                            Teknik Informatika</option>
                                                        <option value="Sistem Informasi"
                                                            {{ old('jurusan', $user->jurusan) == 'Sistem Informasi' ? 'selected' : '' }}>
                                                            Sistem Informasi</option>
                                                        <option value="Manajemen"
                                                            {{ old('jurusan', $user->jurusan) == 'Manajemen' ? 'selected' : '' }}>
                                                            Manajemen</option>
                                                        <option value="Akuntansi"
                                                            {{ old('jurusan', $user->jurusan) == 'Akuntansi' ? 'selected' : '' }}>
                                                            Akuntansi</option>
                                                    </select>
                                                    @error('jurusan')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="status{{ $user->id }}" class="form-label">Status</label>
                                                    <select class="form-control @error('status') is-invalid @enderror"
                                                        id="status{{ $user->id }}" name="status" required>
                                                        <option value="1"
                                                            {{ old('status', $user->status) == 1 ? 'selected' : '' }}>
                                                            Active</option>
                                                        <option value="0"
                                                            {{ old('status', $user->status) == 0 ? 'selected' : '' }}>
                                                            Inactive</option>
                                                    </select>
                                                    @error('status')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
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
                        @endforeach
                    </tbody>
                </table>
                <div class="modal fade @if ($errors->any() && !old('id')) show @endif" id="createModal" tabindex="-1"
                    aria-labelledby="createModalLabel" @if ($errors->any() && !old('id')) style="display: block;"
                    aria-modal="true"
                    @else aria-hidden="true" @endif>
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
                                        <input type="text" class="form-control @error('username') is-invalid @enderror"
                                            id="username" name="username" value="{{ old('username') }}" required>
                                        @error('username')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Email -->
                                    <div class="form-group mb-3">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email') }}" required>
                                        @error('email')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- No HP -->
                                    <div class="form-group mb-3">
                                        <label for="no_hp">No HP</label>
                                        <input type="text" class="form-control @error('no_hp') is-invalid @enderror"
                                            id="no_hp" name="no_hp" value="{{ old('no_hp') }}" required>
                                        @error('no_hp')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Password -->
                                    <div class="form-group mb-3 position-relative">
                                        <label for="password3">Password</label>
                                        <div class="input-group">
                                            <input type="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                id="password3" name="password" required>
                                            <button type="button" id="togglePassword3"
                                                class="btn btn-outline-secondary toggle-password">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                        @error('password')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Confirm Password -->
                                    <div class="form-group mb-3 position-relative">
                                        <label for="password_confirmation2">Confirm Password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="password_confirmation2"
                                                name="password_confirmation" required>
                                            <button type="button" id="togglePassword4"
                                                class="btn btn-outline-secondary toggle-password">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Jurusan (Select Option) -->
                                    <div class="form-group mb-3">
                                        <label for="jurusan">Jurusan</label>
                                        <select class="form-control @error('jurusan') is-invalid @enderror" id="jurusan"
                                            name="jurusan" required>
                                            <option value="Teknik Informatika"
                                                {{ old('jurusan') == 'Teknik Informatika' ? 'selected' : '' }}>Teknik
                                                Informatika</option>
                                            <option value="Sistem Informasi"
                                                {{ old('jurusan') == 'Sistem Informasi' ? 'selected' : '' }}>Sistem
                                                Informasi</option>
                                            <option value="Manajemen"
                                                {{ old('jurusan') == 'Manajemen' ? 'selected' : '' }}>Manajemen</option>
                                            <option value="Akuntansi"
                                                {{ old('jurusan') == 'Akuntansi' ? 'selected' : '' }}>Akuntansi</option>
                                        </select>
                                        @error('jurusan')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Address -->
                                    <div class="form-group mb-3">
                                        <label for="address">Address</label>
                                        <textarea class="form-control @error('address') is-invalid @enderror"
                                            id="address" name="address" required>{{ old('address') }}</textarea>
                                        @error('address')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Role Name -->
                                    <div class="form-group mb-3">
                                        <label for="role_name">Role</label>
                                        <select class="form-control @error('role_name') is-invalid @enderror"
                                            id="role_name" name="role_name" required>
                                            <option value="User" {{ old('role_name') == 'User' ? 'selected' : '' }}>User
                                            </option>
                                            <option value="Admin" {{ old('role_name') == 'Admin' ? 'selected' : '' }}>
                                                Admin</option>
                                        </select>
                                        @error('role_name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
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



                <!-- Pagination -->
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

const toggle3 = document.getElementById('togglePassword3');
toggle3.addEventListener('click', function() {
    const passwordField = document.getElementById('password3');
    const icon = this.querySelector('svg');

    // Toggle visibility
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        passwordField.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
});
$('#createUserForm').on('submit', function(e) {
    e.preventDefault();

    let formData = {
        _token: $('input[name=_token]').val(),
        username: $('#username').val(),
        email: $('#email').val(),
        no_hp: $('#no_hp').val(),
        address: $('#address').val(),
        jurusan: $('#jurusan').val(),
        role_name: $('#role_name').val(),
        password: $('#password3').val(),
        password_confirmation: $('#password_confirmation2').val(),
    };

    $.ajax({
        url: "/admin/users/store",
        type: "POST",
        data: formData,
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: response.message,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
                $('#createModal').modal('hide');
                location.reload(); // Refresh halaman
            }
        },
        error: function(xhr) {
            console.log("Error Response:", xhr.responseJSON);

            if (xhr.status === 422) {
                let errors = xhr.responseJSON.errors;

                // Hapus error sebelumnya
                $('.invalid-feedback').remove();
                $('.is-invalid').removeClass('is-invalid');

                // Tampilkan error baru
                $.each(errors, function(key, value) {
                    let input = $('[name=' + key + ']');
                    input.addClass('is-invalid');
                    input.after('<div class="invalid-feedback">' + value[0] + '</div>');
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON.message || 'An unexpected error occurred.',
                });
            }
        },
    });
});




$(document).ready(function () {
    let editModalId = null;
    $(document).on('click', '[data-bs-target^="#editModal"]', function() {
    let targetModal = $($(this).data('bs-target'));
    editModalId = targetModal.attr('id');
    console.log('Opening Modal:', targetModal.attr('id'));

        // Handle form submission for edit user
        $(document).on('submit', `#${editModalId}`, function (e) {
            e.preventDefault(); // Prevent default form submission
            
            let form = $(this);
            let userId = form.find('input[name="id"]').val(); // Get user ID from hidden input

            // Debugging
            console.log('Submitting Form:', form);
            console.log('User  ID:', userId);

            let actionUrl = '/admin/users/update/' + userId; // Set the action URL

            $.ajax({
                url: actionUrl,
                type: 'PUT',
                data: form, // Serialize form data
                success: function (response) {
                    console.log('Response:', response); // Log the response

                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message,
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                        }).then(() => {
                            // Redirect to the user datatable after the success message
                            window.location.href = '/admin/user-table'; // Redirect to the user datatable
                        });
                    }
                },
                error: function (xhr) {
                    let response = xhr.responseJSON;

                    // Clear previous error messages
                    form.find('.invalid-feedback').remove();
                    form.find('input, select, textarea').removeClass('is-invalid');

                    if (response.errors) {
                        // Display validation errors
                        for (const [key, value] of Object.entries(response.errors)) {
                            let errorElement = form.find(`#${key}Error`);
                            if (errorElement.length) {
                                errorElement.text(value.join(' '));
                            } else {
                                form.find(`#${key}`).addClass('is-invalid');
                                form.find(`#${key}`).after(`<div class="invalid-feedback" id="${key}Error">${value.join(' ')}</div>`);
                            }
                        }
                        return; // Keep the modal open if there are errors
                    } else {
                        // Display general error message
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message || 'Something went wrong.',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                        });
                    }
                }
            });
        });
});

    // Reset validation messages when the modal is closed
    $(document).on('hidden.bs.modal', function () {
        $(this).find('.invalid-feedback').remove();
        $(this).find('input, select, textarea').removeClass('is-invalid');
    });
});
</script>
@endsection
