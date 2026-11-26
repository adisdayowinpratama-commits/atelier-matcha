<div class="card card-menu">
    <img src="{{ $menu->image_url }}" class="card-img-top" alt="{{ $menu->name }}">
    <div class="card-body d-flex flex-column">
        <small class="text-matcha">{{ $menu->category->name }}</small>
        <h5 class="card-title">{{ $menu->name }}</h5>
        <p class="card-text small text-muted">{{ \Illuminate\Support\Str::limit($menu->description, 70) }}</p>
        <div class="mt-auto d-flex justify-content-between align-items-center">
            <strong>Rp {{ number_format($menu->price, 0, ',', '.') }}</strong>
            <a href="{{ route('menu.show', $menu) }}" class="btn btn-outline-matcha btn-sm">Detail</a>
        </div>
    </div>
</div>
