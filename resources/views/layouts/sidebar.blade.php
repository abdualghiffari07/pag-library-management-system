<aside class="sidebar" id="sidebar">

    <div class="brand">

        <div class="brand-mark">
            📚
        </div>

        <div class="brand-text">

            <div class="brand-title">
                PAG <span>LIBRARY</span>
            </div>

            <div class="brand-subtitle">
                Sistem Perpustakaan
            </div>

        </div>

    </div>


<nav class="menu">

    {{-- =====================================================
         KOLEKSI
    ====================================================== --}}

    <div class="menu-section">
        KOLEKSI
    </div>

    <a href="{{ route('books.index') }}"
       class="menu-item {{ request()->routeIs('books.*') ? 'active' : '' }}">

        <span class="menu-icon">
            ▣
        </span>

        <span>
            Data Buku
        </span>

    </a>


    <a href="{{ route('authors.index') }}"
       class="menu-item {{ request()->routeIs('authors.*') ? 'active' : '' }}">

        <span class="menu-icon">
            ♙
        </span>

        <span>
            Penulis
        </span>

    </a>


    {{-- =====================================================
         PENGUNJUNG
    ====================================================== --}}

    <div class="menu-section">
        PENGUNJUNG
    </div>

    <div class="menu-item menu-disabled">

        <span class="menu-icon">
            ♙
        </span>

        <span>
            Daftar Pengunjung
        </span>

    </div>


    {{-- =====================================================
         LAPORAN
    ====================================================== --}}

    <div class="menu-section">
        LAPORAN
    </div>

    <a href="{{ route('reports.index') }}"
       class="menu-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">

        <span class="menu-icon">
            ▥
        </span>

        <span>
            Laporan
        </span>

    </a>

</nav>

</aside>