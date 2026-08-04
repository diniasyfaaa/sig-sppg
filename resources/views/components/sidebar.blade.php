<div class="sidebar" id="sidebar">

    <div class="logo-area">

        <h3>SIG SPPG</h3>

        <p>Kecamatan Cileunyi</p>

    </div>

    <div class="menu">

        <a
            href="{{ route('admin.dashboard') }}"
            class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
        >
            <i class="bi bi-speedometer2"></i>
            <span>Dashboard</span>
        </a>

        <a
            href="{{ route('peta.index') }}"
            class="sidebar-link {{ request()->routeIs('peta.*') ? 'active' : '' }}"
        >
            <i class="bi bi-geo-alt-fill"></i>
            <span>Peta Sebaran</span>
        </a>

        <a
            href="{{ route('sppg.index') }}"
            class="sidebar-link {{ request()->routeIs('sppg.*') || request()->routeIs('sasaran.*') ? 'active' : '' }}"
        >
            <i class="bi bi-building"></i>
            <span>Data SPPG</span>
        </a>

        <a
            href="{{ route('admin.statistik') }}"
            class="sidebar-link {{ request()->routeIs('admin.statistik') ? 'active' : '' }}"
        >
            <i class="bi bi-bar-chart-fill"></i>
            <span>Statistik</span>
        </a>

    </div>

    <div class="logout">

        <form action="{{ route('logout') }}" method="POST">
            @csrf

            <button
                type="submit"
                class="sidebar-logout-button"
            >
                <i class="bi bi-box-arrow-right"></i>
                <span>Logout</span>
            </button>

        </form>

    </div>

</div>