<nav class="user-navbar fixed-top">
    <div class="user-navbar-inner">

        {{-- Brand --}}
        <a href="{{ route('kepala.dashboard') }}" class="user-navbar-brand">
            <img src="{{ asset('images/logo-cileunyi.jpg') }}" alt="Logo SIG SPPG" class="user-navbar-logo">

            <div class="user-brand-text">
                <span class="user-brand-title">
                    SIG SPPG
                </span>

                <span class="user-brand-subtitle">
                    Panel Kepala SPPG
                </span>
            </div>
        </a>

        {{-- Menu --}}
        <div class="user-navbar-menu">

            <a href="{{ route('kepala.dashboard') }}"
                class="user-nav-link {{ request()->routeIs('kepala.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2 me-1"></i> Dashboard
            </a>

            <a href="{{ route('kepala.permohonan.index') }}"
                class="user-nav-link {{ request()->routeIs('kepala.permohonan.*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-text me-1"></i> Permohonan Saya
            </a>

            <a href="{{ route('user.peta') }}" class="user-nav-link" target="_blank">
                <i class="bi bi-geo-alt me-1"></i> Lihat Peta
            </a>

            <span class="user-navbar-divider"></span>

            {{-- Dropdown Profile --}}
            <div class="position-relative">
                <button type="button" class="admin-profile-button" id="adminProfileButton">
                    <span class="admin-profile-avatar">
                        <i class="bi bi-person-fill"></i>
                    </span>

                    <span class="admin-profile-text">
                        <small>Masuk sebagai</small>
                        <strong>{{ Auth::user()->name ?? 'Kepala SPPG' }}</strong>
                    </span>

                    <i class="bi bi-chevron-down admin-profile-chevron"></i>
                </button>

                <div class="admin-profile-dropdown" id="adminProfileDropdown">
                    <div class="admin-dropdown-header">
                        <strong>{{ Auth::user()->name ?? 'Kepala SPPG' }}</strong>
                        <small>{{ Auth::user()->email ?? '' }}</small>
                        <span class="badge bg-primary-subtle text-primary mt-1 align-self-start"
                            style="font-size: 11px;">Kepala SPPG</span>
                    </div>

                    <hr class="admin-dropdown-divider">

                    <a href="{{ route('kepala.password') }}"
                        class="admin-dropdown-item {{ request()->routeIs('kepala.password') ? 'active' : '' }}">
                        <i class="bi bi-key-fill"></i>
                        <span>Ganti Password</span>
                    </a>

                    <hr class="admin-dropdown-divider">

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf

                        <button type="submit" class="admin-logout-button">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</nav>