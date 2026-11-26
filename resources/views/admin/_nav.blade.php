<ul class="nav admin-nav flex-wrap gap-1 mb-4">
    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">Kategori</a></li>
    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.menus.*') ? 'active' : '' }}" href="{{ route('admin.menus.index') }}">Menu</a></li>
    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.ingredients.*') ? 'active' : '' }}" href="{{ route('admin.ingredients.index') }}">Bahan Racik</a></li>
    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">Pesanan</a></li>
</ul>
