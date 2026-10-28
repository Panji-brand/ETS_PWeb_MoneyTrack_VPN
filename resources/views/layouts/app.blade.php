<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MoneyTrack | Personal Finance Tracker')</title>
    @vite(['resources/css/dashboard.css'])
</head>
<body>
    <div class="layout">
        @include('components.sidebar')

        <main class="main-content">
            @include('components.header')

            @yield('content')
        </main>
    </div>
</body>
</html>