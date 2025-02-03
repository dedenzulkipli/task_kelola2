<nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
    <div class="sb-sidenav-menu">
        <div class="nav">
            <div class="sb-sidenav-menu-heading">Core</div>
            <a class="nav-link" href="{{ url('/') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                Dashboard
            </a>

            <!-- Hanya Admin yang bisa mengakses -->
            @if(auth()->user()->role_name === 'Admin')
                <div class="sb-sidenav-menu-heading">Addons</div>
                <a class="nav-link" href="{{ route('user-table') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                    User DataTable
                </a>
            @endif
        </div>
    </div>
    <div class="sb-sidenav-footer">
        <div class="small">Logged in as:</div>
        {{ Auth::user()->username }}
    </div>
</nav>
