<!-- HEADER -->
<header class="header">
    <div>
        <h1>@yield('page-title', 'Dashboard')</h1>
        <p>@yield('page-description', 'Selamat datang kembali! Berikut ringkasan keuanganmu.')</p>
    </div>
    <div class="profile" style="display:flex;align-items:center;gap:12px;">
        <div class="profile-icon">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
        <div><strong>{{ auth()->user()->name }}</strong><p>{{ auth()->user()->email }}</p>
            <form action="{{ route('logout') }}" method="POST" style="margin-top:6px">@csrf<button type="submit" style="cursor:pointer">Logout</button></form>
        </div>
    </div>
</header>
