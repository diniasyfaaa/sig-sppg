<nav class="user-navbar fixed-top">
    <div class="user-navbar-inner">

        {{-- Brand --}}
        <a
            href="{{ route('user.dashboard') }}"
            class="user-navbar-brand"
        >
            <img
                src="{{ asset('images/logo-cileunyi.jpg') }}"
                alt="Logo SIG SPPG"
                class="user-navbar-logo"
            >

            <div class="user-brand-text">
                <span class="user-brand-title">
                    SIG SPPG
                </span>

                <span class="user-brand-subtitle">
                    Kecamatan Cileunyi
                </span>
            </div>
        </a>

        {{-- Menu --}}
        <div class="user-navbar-menu">

            <a
                href="{{ route('user.dashboard') }}"
                class="user-nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}"
            >
                Home
            </a>

            <a
                href="{{ route('user.peta') }}"
                class="user-nav-link {{ request()->routeIs('user.peta') ? 'active' : '' }}"
            >
                Peta Sebaran
            </a>

            <a
                href="{{ route('user.sppg.index') }}"
                class="user-nav-link {{ request()->routeIs('user.sppg.*') || request()->routeIs('user.sasaran.*') ? 'active' : '' }}"
            >
                Data SPPG
            </a>

            <a
                href="{{ route('user.statistik') }}"
                class="user-nav-link {{ request()->routeIs('user.statistik') ? 'active' : '' }}"
            >
                Statistik
            </a>

            <span class="user-navbar-divider"></span>

            <a
                href="{{ route('login') }}"
                class="user-login-btn"
            >
                Login
            </a>

        </div>

    </div>
</nav>