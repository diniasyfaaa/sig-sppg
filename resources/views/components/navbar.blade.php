<nav class="user-navbar fixed-top">
    <div class="user-navbar-inner">

        {{-- Brand --}}
        <a
            href="{{ Auth::check() && Auth::user()->role === 'kepala_sppg' ? route('kepala.dashboard') : route('user.dashboard') }}"
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

            @if(Auth::check() && Auth::user()->role === 'kepala_sppg')
                <a
                    href="{{ route('kepala.dashboard') }}"
                    class="user-nav-link {{ request()->routeIs('kepala.dashboard') ? 'active' : '' }}"
                >
                    Dashboard
                </a>

                <a
                    href="{{ route('kepala.permohonan.index') }}"
                    class="user-nav-link {{ request()->routeIs('kepala.permohonan.*') ? 'active' : '' }}"
                >
                    Permohonan Saya
                </a>

                @if(Auth::user()->sppg_id)
                    <a
                        href="{{ route('user.sppg.show', Auth::user()->sppg_id) }}"
                        class="user-nav-link {{ request()->routeIs('user.sppg.show') ? 'active' : '' }}"
                    >
                        Detail SPPG Saya
                    </a>

                    <a
                        href="{{ route('user.sasaran.index', Auth::user()->sppg_id) }}"
                        class="user-nav-link {{ request()->routeIs('user.sasaran.*') ? 'active' : '' }}"
                    >
                        Sasaran SPPG
                    </a>
                @endif
            @else
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
            @endif

            <span class="user-navbar-divider"></span>

            @auth
                @php
                    $hasPermohonan = class_exists(\App\Models\PermohonanUpdateSppg::class) && \Illuminate\Support\Facades\Schema::hasTable('permohonan_update_sppg');
                    $pendingPermohonanCount = 0;
                    $kepalaNotificationCount = 0;
                    if ($hasPermohonan) {
                        if (Auth::user()->role === 'admin') {
                            $pendingPermohonanCount = \App\Models\PermohonanUpdateSppg::where('status', 'pending')->count();
                        } elseif (Auth::user()->role === 'kepala_sppg') {
                            $kepalaNotificationCount = \App\Models\PermohonanUpdateSppg::where('user_id', Auth::id())
                                ->whereIn('status', ['disetujui', 'ditolak'])
                                ->where('is_read_kepala', false)
                                ->count();
                        }
                    }
                @endphp

                @if(Auth::user()->role === 'admin' && Route::has('admin.permohonan.index'))
                    <a href="{{ route('admin.permohonan.index') }}" class="position-relative text-dark text-decoration-none me-3" title="Permohonan Update SPPG @if($pendingPermohonanCount > 0)({{ $pendingPermohonanCount }} pending)@endif">
                        <i class="bi bi-bell text-secondary fs-4"></i>
                        @if($pendingPermohonanCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 10px; padding: 3px 6px;">
                                {{ $pendingPermohonanCount > 99 ? '99+' : $pendingPermohonanCount }}
                            </span>
                        @endif
                    </a>
                @elseif(Auth::user()->role === 'kepala_sppg' && Route::has('kepala.permohonan.index'))
                    <a href="{{ route('kepala.permohonan.index') }}" class="position-relative text-dark text-decoration-none me-3" title="Permohonan Saya @if($kepalaNotificationCount > 0)({{ $kepalaNotificationCount }} notifikasi baru)@endif">
                        <i class="bi bi-bell text-secondary fs-4"></i>
                        @if($kepalaNotificationCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 10px; padding: 3px 6px;">
                                {{ $kepalaNotificationCount > 99 ? '99+' : $kepalaNotificationCount }}
                            </span>
                        @endif
                    </a>
                @endif

                <div class="position-relative">
                    <button
                        type="button"
                        class="admin-profile-button"
                        id="userNavProfileButton"
                    >
                        <span class="admin-profile-avatar" style="width: 38px; height: 38px; font-size: 16px;">
                            <i class="bi bi-person-fill"></i>
                        </span>

                        <span class="admin-profile-text">
                            <small>Masuk sebagai</small>
                            <strong>{{ Auth::user()->name ?? 'User' }}</strong>
                        </span>

                        <i class="bi bi-chevron-down admin-profile-chevron"></i>
                    </button>

                    <div
                        class="admin-profile-dropdown"
                        id="userNavProfileDropdown"
                        style="right: 0; left: auto;"
                    >
                        <div class="admin-dropdown-header">
                            <strong>{{ Auth::user()->name ?? 'User' }}</strong>
                            <small>{{ Auth::user()->email ?? '' }}</small>
                            @if(Auth::user()->role === 'kepala_sppg')
                                <span class="badge bg-primary-subtle text-primary mt-1 align-self-start" style="font-size: 11px;">Kepala SPPG</span>
                            @elseif(Auth::user()->role === 'admin')
                                <span class="badge bg-secondary-subtle text-secondary mt-1 align-self-start" style="font-size: 11px;">Administrator</span>
                            @endif
                        </div>

                        <hr class="admin-dropdown-divider">

                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="admin-dropdown-item">
                                <i class="bi bi-speedometer2"></i>
                                <span>Panel Admin</span>
                            </a>
                            <a href="{{ route('admin.permohonan.index') }}" class="admin-dropdown-item">
                                <i class="bi bi-bell-fill"></i>
                                <span>Permohonan Update</span>
                            </a>
                            <a href="{{ route('users.index') }}" class="admin-dropdown-item">
                                <i class="bi bi-people-fill"></i>
                                <span>Kelola User</span>
                            </a>
                            <a href="{{ route('profile.password') }}" class="admin-dropdown-item">
                                <i class="bi bi-key-fill"></i>
                                <span>Ganti Password</span>
                            </a>
                        @elseif(Auth::user()->role === 'kepala_sppg')
                            <a href="{{ route('kepala.password') }}" class="admin-dropdown-item">
                                <i class="bi bi-key-fill"></i>
                                <span>Ganti Password</span>
                            </a>
                        @endif

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
            @endauth
        </div>
    </div>
</nav>