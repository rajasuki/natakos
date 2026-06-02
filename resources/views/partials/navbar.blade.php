{{-- Universal navbar — dipakai oleh public.layout dan admin.layout --}}
<header class="site-header">
    <div class="site-shell header-row">

        <a href="{{ route('home') }}" class="brand-link">
            <span class="brand-mark">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9.5L12 3l9 6.5V21a1 1 0 01-1 1H4a1 1 0 01-1-1V9.5z"/>
                    <path d="M9 21V12h6v9"/>
                </svg>
            </span>
            <span class="brand-text">Ichi<strong>KOS</strong></span>
        </a>

        <nav class="nav-links desktop-nav" aria-label="Navigasi">
            <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'is-active' : '' }}" @if(request()->routeIs('home')) aria-current="page" @endif>Home</a>
            <a href="{{ route('rooms.index') }}" class="nav-link {{ request()->routeIs('rooms.*') ? 'is-active' : '' }}" @if(request()->routeIs('rooms.*')) aria-current="page" @endif>Kamar</a>
            <a href="{{ route('home') }}#fasilitas" class="nav-link">Fasilitas</a>
            <a href="{{ route('home') }}#lokasi" class="nav-link">Lokasi</a>
            <a href="{{ route('home') }}#kontak" class="nav-link">Kontak</a>

            @auth
                @if(Auth::user()->role === 'admin')
                <div class="nav-item has-dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-nav-dropdown>
                        Dashboard
                        <svg class="nav-chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
                    </a>
                    <div class="nav-dropdown">
                        <a href="{{ route('admin.dashboard') }}" class="dropdown-link {{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}">Dashboard</a>
                        <a href="{{ route('admin.rooms.index') }}" class="dropdown-link {{ request()->routeIs('admin.rooms.*') ? 'is-active' : '' }}">Kamar</a>
                        <a href="{{ route('admin.facilities.index') }}" class="dropdown-link {{ request()->routeIs('admin.facilities.*') ? 'is-active' : '' }}">Fasilitas</a>
                        <a href="{{ route('admin.tenants.index') }}" class="dropdown-link {{ request()->routeIs('admin.tenants.*') ? 'is-active' : '' }}">Penghuni</a>
                        <a href="{{ route('admin.payments.index') }}" class="dropdown-link {{ request()->routeIs('admin.payments.*') ? 'is-active' : '' }}">Pembayaran</a>
                        <a href="{{ route('admin.kos-profile.edit') }}" class="dropdown-link {{ request()->routeIs('admin.kos-profile.*') ? 'is-active' : '' }}">Profil</a>
                    </div>
                </div>
                @elseif(Auth::user()->role === 'tenant')
                <a href="{{ route('tenant.dashboard') }}" class="nav-link {{ request()->routeIs('tenant.dashboard') ? 'is-active' : '' }}" @if(request()->routeIs('tenant.dashboard')) aria-current="page" @endif>Dashboard</a>
                @endif
            @endauth
        </nav>

        <div class="header-actions">
            @auth
                <span class="nav-greeting">Halo, {{ Auth::user()->role === 'admin' ? 'Admin' : Auth::user()->name }}!</span>
                <form method="POST" action="{{ route('logout') }}" class="nav-logout-form">
                    @csrf
                    <button type="submit" class="nav-auth-link">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="nav-auth-link">Sign</a>
            @endauth

            <details class="mobile-menu">
                <summary class="mobile-menu-toggle" aria-label="Buka navigasi">
                    <svg width="22" height="22" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </summary>

                <div class="mobile-menu-panel">
                    <nav class="mobile-nav-links" aria-label="Navigasi seluler">
                        <a href="{{ route('home') }}" class="mobile-nav-link {{ request()->routeIs('home') ? 'is-active' : '' }}" @if(request()->routeIs('home')) aria-current="page" @endif>Home</a>
                        <a href="{{ route('rooms.index') }}" class="mobile-nav-link {{ request()->routeIs('rooms.*') ? 'is-active' : '' }}" @if(request()->routeIs('rooms.*')) aria-current="page" @endif>Kamar</a>
                        <a href="{{ route('home') }}#fasilitas" class="mobile-nav-link">Fasilitas</a>
                        <a href="{{ route('home') }}#lokasi" class="mobile-nav-link">Lokasi</a>
                        <a href="{{ route('home') }}#kontak" class="mobile-nav-link">Kontak</a>

                        @auth
                            @if(Auth::user()->role === 'admin')
                            <div class="mobile-nav-divider"></div>
                            <span class="mobile-nav-label">Admin</span>
                            <a href="{{ route('admin.dashboard') }}" class="mobile-nav-link {{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}">Dashboard</a>
                            <a href="{{ route('admin.rooms.index') }}" class="mobile-nav-link {{ request()->routeIs('admin.rooms.*') ? 'is-active' : '' }}">Kamar</a>
                            <a href="{{ route('admin.facilities.index') }}" class="mobile-nav-link {{ request()->routeIs('admin.facilities.*') ? 'is-active' : '' }}">Fasilitas</a>
                            <a href="{{ route('admin.tenants.index') }}" class="mobile-nav-link {{ request()->routeIs('admin.tenants.*') ? 'is-active' : '' }}">Penghuni</a>
                            <a href="{{ route('admin.payments.index') }}" class="mobile-nav-link {{ request()->routeIs('admin.payments.*') ? 'is-active' : '' }}">Pembayaran</a>
                            <a href="{{ route('admin.kos-profile.edit') }}" class="mobile-nav-link {{ request()->routeIs('admin.kos-profile.*') ? 'is-active' : '' }}">Profil</a>
                            @elseif(Auth::user()->role === 'tenant')
                            <a href="{{ route('tenant.dashboard') }}" class="mobile-nav-link {{ request()->routeIs('tenant.dashboard') ? 'is-active' : '' }}" @if(request()->routeIs('tenant.dashboard')) aria-current="page" @endif>Dashboard</a>
                            @endif
                            <div class="mobile-nav-divider"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="mobile-nav-link" style="color: #9f1239; border:0; background:transparent; cursor:pointer; width:100%;">Logout</button>
                            </form>
                        @endauth
                    </nav>
                </div>
            </details>
        </div>

    </div>
</header>
