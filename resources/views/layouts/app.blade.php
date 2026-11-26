<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Atelier Matcha') | Craft Your Own Matcha</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --matcha: #4a7c59; --matcha-dark: #365a41; --matcha-light: #e4efd9; --cream: #faf6ec; }
        body { background: var(--cream); color: #2f3b2f; }
        .btn-matcha { background: var(--matcha); color: #fff; border: 0; }
        .btn-matcha:hover { background: var(--matcha-dark); color: #fff; }
        .btn-outline-matcha { border: 1px solid var(--matcha); color: var(--matcha); }
        .btn-outline-matcha:hover { background: var(--matcha); color: #fff; }
        .btn-check:checked + .btn-outline-matcha { background: var(--matcha); color: #fff; border-color: var(--matcha); }
        .btn-check:focus-visible + .btn-outline-matcha { box-shadow: 0 0 0 .25rem rgba(74,124,89,.35); }
        .text-matcha { color: var(--matcha) !important; }
        .bg-matcha-light { background: var(--matcha-light); }
        .navbar { background: #fff; box-shadow: 0 2px 8px rgba(0,0,0,.06); }
        .brand-text { font-family: Georgia, serif; font-weight: bold; color: var(--matcha-dark); }
        .hero { background: linear-gradient(135deg, var(--matcha-light), var(--cream)); }
        .card-menu { border: 0; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 14px rgba(0,0,0,.07); transition: transform .2s; height: 100%; }
        .card-menu:hover { transform: translateY(-4px); }
        .card-menu img { height: 190px; object-fit: cover; }
        .thumb { width: 48px; height: 48px; object-fit: cover; border-radius: 8px; }
        .admin-nav .nav-link { color: var(--matcha-dark); border-radius: 999px; padding: .35rem 1rem; }
        .admin-nav .nav-link.active { background: var(--matcha); color: #fff; }
        footer { background: var(--matcha-dark); color: #dcebd2; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
            @include('partials.logo', ['size' => 38])
            <span class="brand-text">Atelier Matcha</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="nav">
            <ul class="navbar-nav me-auto ms-lg-4">
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Beranda</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('menu.index') }}">Menu</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('racik') }}">Racik Matcha</a></li>
            </ul>
            <ul class="navbar-nav align-items-lg-center gap-lg-2">
                @guest
                    <li class="nav-item"><a class="btn btn-outline-matcha btn-sm" href="{{ route('login') }}">Masuk</a></li>
                    <li class="nav-item"><a class="btn btn-matcha btn-sm" href="{{ route('register') }}">Daftar</a></li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('cart.index') }}">
                            Keranjang
                            @if (\App\Support\Cart::count() > 0)
                                <span class="badge bg-success rounded-pill">{{ \App\Support\Cart::count() }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">{{ auth()->user()->name }}</a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            @if (auth()->user()->isAdmin())
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">Panel Admin</a></li>
                                <li><hr class="dropdown-divider"></li>
                            @endif
                            <li><a class="dropdown-item" href="{{ route('recipes.index') }}">Racikan Saya</a></li>
                            <li><a class="dropdown-item" href="{{ route('orders.index') }}">Pesanan Saya</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="dropdown-item" type="submit">Keluar</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

<main>
    @if (session('success'))
        <div class="container mt-3"><div class="alert alert-success mb-0">{{ session('success') }}</div></div>
    @endif
    @if (session('error'))
        <div class="container mt-3"><div class="alert alert-danger mb-0">{{ session('error') }}</div></div>
    @endif

    @yield('content')
</main>

<footer class="mt-5 py-4">
    <div class="container text-center small">
        &copy; {{ date('Y') }} Atelier Matcha. Craft Your Own Matcha.
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
