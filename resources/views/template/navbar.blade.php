<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <div class="container-fluid">
        <!-- Logo dan Sidebar Toggle -->
        <div class="d-flex align-items-center">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ asset('assets/img/images.jpeg') }}" alt="Logo" style="height: 30px;"> elola.biz
            </a>
            <button class="btn btn-link btn-sm ms-3" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
        </div>

        <!-- User Dropdown -->
        <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <i class="fas fa-user fa-fw"></i> {{ Auth::user()->name }} ({{ Auth::user()->username }})
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#profileModal">
                            <i class="fas fa-user"></i> My Profile
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item"><i class="fas fa-sign-out-alt"></i> Logout</button>
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>

<!-- Modal My Profile -->
<div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="profileModalLabel"><i class="fas fa-user"></i> My Profile</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <table class="table table-borderless">
                    <!-- <tr>
                        <th>Nama</th>
                        <td>{{ Auth::user()->name }}</td>
                    </tr> -->
                    <tr>
                        <th>Username</th>
                        <td>{{ Auth::user()->username }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ Auth::user()->email }}</td>
                    </tr>
                    <tr>
                        <th>No HP</th>
                        <td>{{ Auth::user()->no_hp }}</td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td>{{ Auth::user()->address }}</td>
                    </tr>
                    <tr>
                        <th>Jurusan</th>
                        <td>{{ Auth::user()->jurusan }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            @if(Auth::user()->status)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Role</th>
                        <td>{{ Auth::user()->role_name }}</td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>
