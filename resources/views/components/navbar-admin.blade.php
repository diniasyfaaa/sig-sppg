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

    <div class="admin-navbar-right">

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
                <strong>Admin</strong>
            </span>

            <i class="bi bi-chevron-down admin-profile-chevron"></i>
        </button>

        <div
            class="admin-profile-dropdown"
            id="adminProfileDropdown"
        >
            <form action="{{ route('logout') }}" method="POST">
                @csrf

                <button
                    type="submit"
                    class="admin-logout-button"
                >
                    <i class="bi bi-box-arrow-right"></i>
                    Logout
                </button>
            </form>
        </div>

    </div>

</nav>