<aside class="sidebar" id="sidebar">

    {{-- =====================================================
         BRAND
    ====================================================== --}}

    <div class="brand">

        <div class="brand-logo">
            <img src="{{ asset('images/landing/logo-pertamina.png') }}"
                 alt="PAG Library">
        </div>

        <div class="brand-text">

            <div class="brand-title">
                Perpustakaan <span>Perta Arun Gas</span>
            </div>

            <div class="brand-subtitle">
                Sistem Perpustakaan
            </div>

        </div>

    </div>


    {{-- =====================================================
         MENU
    ====================================================== --}}

    <nav class="menu">

        {{-- DATA BUKU --}}

        <a href="{{ route('books.index') }}"
           class="menu-item {{ request()->routeIs('books.*') ? 'active' : '' }}">

            <span class="menu-icon">
                <img src="{{ asset('images/book.png') }}"
                     alt="Data Buku">
            </span>

            <span>
                Data Buku
            </span>

        </a>


        {{-- PENULIS --}}

        <a href="{{ route('authors.index') }}"
           class="menu-item {{ request()->routeIs('authors.*') ? 'active' : '' }}">

            <span class="menu-icon">
                <img src="{{ asset('images/authors.png') }}"
                     alt="Penulis">
            </span>

            <span>
                Penulis
            </span>

        </a>


        {{-- DAFTAR PENGUNJUNG --}}

        <div class="menu-item menu-disabled">

            <span class="menu-icon">
                <img src="{{ asset('images/visitors.png') }}"
                     alt="Daftar Pengunjung">
            </span>

            <span>
                Daftar Pengunjung
            </span>

        </div>


        {{-- LAPORAN --}}

        <a href="{{ route('reports.index') }}"
           class="menu-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">

            <span class="menu-icon">
                <img src="{{ asset('images/report.png') }}"
                     alt="Laporan">
            </span>

            <span>
                Laporan
            </span>

        </a>

    </nav>


    {{-- =====================================================
         LOGOUT
    ====================================================== --}}

    <div class="logout-wrapper">

        <form method="POST"
              action="{{ route('logout') }}"
              onsubmit="return confirm('Apakah Anda yakin ingin keluar?');">

            @csrf

            <button type="submit"
                    class="logout-button">

                <span class="logout-icon">
                    ⇥
                </span>

                <span>
                    Keluar
                </span>

            </button>

        </form>

    </div>

</aside>