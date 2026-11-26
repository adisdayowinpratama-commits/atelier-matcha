@extends('layouts.app')
@section('title', 'Menu')

@section('content')
<div class="container py-5">
    <h1 class="fw-bold brand-text">Menu Matcha</h1>
    <p class="text-muted">Pilihan menu siap pesan dari Atelier Matcha.</p>

    @foreach ($categories as $category)
        @if ($category->menus->isNotEmpty())
            <h4 class="mt-5 mb-3 text-matcha">{{ $category->name }}</h4>
            <div class="row g-4">
                @foreach ($category->menus as $menu)
                    @php $menu->setRelation('category', $category); @endphp
                    <div class="col-12 col-sm-6 col-lg-3">
                        @include('menu._card', ['menu' => $menu])
                    </div>
                @endforeach
            </div>
        @endif
    @endforeach
</div>
@endsection