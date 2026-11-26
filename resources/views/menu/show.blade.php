@extends('layouts.app')
@section('title', $menu->name)

@section('content')
<div class="container py-5">
    <a href="{{ route('menu.index') }}" class="text-matcha">&larr; Kembali ke menu</a>

    <div class="row g-4 mt-2">
        <div class="col-md-6">
            <img src="{{ $menu->image_url }}" class="img-fluid rounded-4 w-100" alt="{{ $menu->name }}">
        </div>
        <div class="col-md-6">
            <span class="badge bg-matcha-light text-matcha">{{ $menu->category->name }}</span>
            <h1 class="fw-bold brand-text mt-2">{{ $menu->name }}</h1>
            <h3 class="text-matcha">Rp {{ number_format($menu->price, 0, ',', '.') }}</h3>
            <p class="mt-3">{{ $menu->description }}</p>
            <p>
                @if ($menu->is_available)
                    <span class="badge bg-success">Tersedia</span>
                @else
                    <span class="badge bg-secondary">Habis</span>
                @endif
            </p>
            @if ($menu->is_available)
                @auth
                    <form method="POST" action="{{ route('cart.add.menu', $menu) }}" class="d-flex gap-2 align-items-center">
                        @csrf
                        <input type="number" name="quantity" value="1" min="1" max="20" class="form-control" style="width:90px">
                        <button class="btn btn-matcha">Tambah ke Keranjang</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-matcha">Masuk untuk memesan</a>
                @endauth
            @endif
        </div>
    </div>

    @if ($related->isNotEmpty())
        <h4 class="mt-5 mb-3 brand-text">Menu lain di kategori ini</h4>
        <div class="row g-4">
            @foreach ($related as $item)
                @php $item->setRelation('category', $menu->category); @endphp
                <div class="col-12 col-sm-6 col-lg-4">
                    @include('menu._card', ['menu' => $item])
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection