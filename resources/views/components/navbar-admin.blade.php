<nav class="admin-navbar">

    <div class="admin-navbar-left">

        <button
            type="button"
            class="admin-menu-toggle"
            id="sidebarToggle"
            aria-label="Buka atau tutup sidebar"
        >
            <i class="bi bi-list"></i>
        </button>

    </div>

    <div class="admin-navbar-right d-flex align-items-center gap-3">

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
                {{-- Notification Bell Admin --}}
                <a href="{{ route('admin.permohonan.index') }}" class="position-relative text-dark text-decoration-none me-3" title="Permohonan Update SPPG @if($pendingPermohonanCount > 0)({{ $pendingPermohonanCount }} pending)@endif">
                    <i class="bi bi-bell text-secondary fs-4"></i>
                    @if($pendingPermohonanCount > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 10px; padding: 3px 6px;">
                            {{ $pendingPermohonanCount > 99 ? '99+' : $pendingPermohonanCount }}
                            <span class="visually-hidden">permohonan pending</span>
                        </span>
                    @endif
                </a>
            @elseif(Auth::user()->role === 'kepala_sppg' && Route::has('kepala.permohonan.index'))
                {{-- Notification Bell Kepala SPPG --}}
                <a href="{{ route('kepala.permohonan.index') }}" class="position-relative text-dark text-decoration-none me-3" title="Permohonan Saya @if($kepalaNotificationCount > 0)({{ $kepalaNotificationCount }} notifikasi baru)@endif">
                    <i class="bi bi-bell text-secondary fs-4"></i>
                    @if($kepalaNotificationCount > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 10px; padding: 3px 6px;">
                            {{ $kepalaNotificationCount > 99 ? '99+' : $kepalaNotificationCount }}
                            <span class="visually-hidden">notifikasi permohonan</span>
                        </span>
                    @endif
                </a>
            @endif

            <button
                type="button"
                class="admin-profile-button"
                id="adminProfileButton"
            >
                <span class="admin-profile-avatar">
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
                id="adminProfileDropdown"
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

                @if(Auth::user()->role === 'kepala_sppg')
                    <a href="{{ route('kepala.password') }}" class="admin-dropdown-item {{ request()->routeIs('kepala.password') ? 'active' : '' }}">
                        <i class="bi bi-key-fill"></i>
                        <span>Ganti Password</span>
                    </a>
                @else
                    <a href="{{ route('users.index') }}" class="admin-dropdown-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <i class="bi bi-people-fill"></i>
                        <span>Kelola User</span>
                    </a>

                    <a href="{{ route('profile.password') }}" class="admin-dropdown-item {{ request()->routeIs('profile.password') ? 'active' : '' }}">
                        <i class="bi bi-key-fill"></i>
                        <span>Ganti Password</span>
                    </a>
                @endif

                <hr class="admin-dropdown-divider">

                <form action="{{ route('logout') }}" method="POST">
                    @csrf

                    <button
                        type="submit"
                        class="admin-logout-button"
                    >
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        @else
            <a href="{{ route('login') }}" class="btn-main-sm">
                <i class="bi bi-box-arrow-in-right"></i>
                <span>Login</span>
            </a>
        @endauth

    </div>

</nav>