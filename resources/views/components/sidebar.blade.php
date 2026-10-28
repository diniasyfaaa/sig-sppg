<div class="sidebar" id="sidebar">

    <div class="logo-area">

        <h3>SIG SPPG</h3>

        <p>Kecamatan Cileunyi</p>

    </div>

    <div class="menu">

        @if(request()->is('admin*'))
            {{-- Navigasi Admin --}}
            <a href="{{ route('admin.dashboard') }}"
                class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('peta.index') }}" class="sidebar-link {{ request()->routeIs('peta.*') ? 'active' : '' }}">
                <i class="bi bi-geo-alt-fill"></i>
                <span>Peta Sebaran</span>
            </a>

            <a href="{{ route('sppg.index') }}"
                class="sidebar-link {{ request()->routeIs('sppg.*') || request()->routeIs('sasaran.*') ? 'active' : '' }}">
                <i class="bi bi-building"></i>
                <span>Data SPPG</span>
            </a>

            <a href="{{ route('admin.menu.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.menu.*') ? 'active' : '' }}">
                <i class="bi bi-journal-richtext"></i>
                <span>Menu Makanan</span>
            </a>

            <a href="{{ route('admin.statistik') }}"
                class="sidebar-link {{ request()->routeIs('admin.statistik') ? 'active' : '' }}">
                <i class="bi bi-bar-chart-fill"></i>
                <span>Statistik</span>
            </a>

            @php
                $hasPermohonan = class_exists(\App\Models\PermohonanUpdateSppg::class) && \Illuminate\Support\Facades\Schema::hasTable('permohonan_update_sppg');
                $pendingPermohonanCount = $hasPermohonan ? \App\Models\PermohonanUpdateSppg::where('status', 'pending')->count() : 0;
            @endphp
            <a href="{{ route('admin.permohonan.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.permohonan.*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-text-fill"></i>
                <span>Permohonan</span>
                @if($pendingPermohonanCount > 0)
                    <span class="badge bg-danger rounded-pill px-2 py-1"
                        style="font-size: 11px;">{{ $pendingPermohonanCount }}</span>
                @endif
            </a>
        @elseif(request()->is('kepala-sppg*'))
            {{-- Navigasi Kepala SPPG --}}
            <a href="{{ route('kepala.dashboard') }}"
                class="sidebar-link {{ request()->routeIs('kepala.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('kepala.permohonan.index') }}"
                class="sidebar-link {{ request()->routeIs('kepala.permohonan.*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-text-fill"></i>
                <span>Permohonan Saya</span>
            </a>

            <a href="{{ route('kepala.menu.index') }}"
                class="sidebar-link {{ request()->routeIs('kepala.menu.*') ? 'active' : '' }}">
                <i class="bi bi-journal-richtext"></i>
                <span>Menu Makanan</span>
            </a>

            <a href="{{ route('kepala.sasaran.index') }}"
                class="sidebar-link {{ request()->routeIs('kepala.sasaran.*') ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i>
                <span>Sasaran</span>
            </a>

        @else
            {{-- Navigasi User / Publik --}}
            <a href="{{ route('user.dashboard') }}"
                class="sidebar-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('user.peta') }}" class="sidebar-link {{ request()->routeIs('user.peta') ? 'active' : '' }}">
                <i class="bi bi-geo-alt-fill"></i>
                <span>Peta Sebaran</span>
            </a>

            <a href="{{ route('user.sppg.index') }}"
                class="sidebar-link {{ request()->routeIs('user.sppg.*') || request()->routeIs('user.sasaran.*') ? 'active' : '' }}">
                <i class="bi bi-building"></i>
                <span>Data SPPG</span>
            </a>

            <a href="{{ route('user.statistik') }}"
                class="sidebar-link {{ request()->routeIs('user.statistik') ? 'active' : '' }}">
                <i class="bi bi-bar-chart-fill"></i>
                <span>Statistik</span>
            </a>
        @endif

    </div>

    <div class="logout">

        @auth
            <form action="{{ route('logout') }}" method="POST">
                @csrf

                <button type="submit" class="sidebar-logout-button">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Logout</span>
                </button>
            </form>
        @else
            <a href="{{ route('login') }}" class="sidebar-logout-button text-decoration-none">
                <i class="bi bi-box-arrow-in-right"></i>
                <span>Login</span>
            </a>
        @endauth

    </div>

</div>