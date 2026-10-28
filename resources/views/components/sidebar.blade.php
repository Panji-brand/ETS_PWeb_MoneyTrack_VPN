<!-- SIDEBAR -->
<aside class="sidebar">
    <div class="brand">
        <div class="brand-icon">M</div>
        <div>
            <h2>MoneyTrack</h2>
            <p>Personal Finance</p>
        </div>
    </div>

    <nav class="menu">
        <div class="menu-title">MENU UTAMA</div>

        <a href="{{ route('dashboard') }}"
           class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
            ▦ &nbsp; Dashboard
        </a>

        <a href="{{ route('transaksi.index') }}"
           class="{{ request()->routeIs('transaksi.*') ? 'active' : '' }}">
            ↔ &nbsp; Transaksi
        </a>

        <a href="{{ route('kategori.index') }}"
           class="{{ request()->routeIs('kategori.*') ? 'active' : '' }}">
            ▤ &nbsp; Kategori
        </a>

        <a href="{{ route('anggaran.index') }}"
           class="{{ request()->routeIs('anggaran.*') ? 'active' : '' }}">
            ◷ &nbsp; Anggaran
        </a>

        <a href="{{ route('laporan.index') }}"
           class="{{ request()->routeIs('laporan.*') ? 'active' : '' }}">
            ▥ &nbsp; Laporan
        </a>

        <div class="menu-title">PREFERENSI</div>

        <a href="{{ route('pengaturan.index') }}"
           class="{{ request()->routeIs('pengaturan.*') ? 'active' : '' }}">
            ⚙ &nbsp; Pengaturan
        </a>
    </nav>
</aside>