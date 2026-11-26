@extends('layouts.app')
@section('title', 'Racikan Saya')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div>
            <h1 class="fw-bold brand-text mb-0">Racikan Saya</h1>
            <p class="text-muted mb-0">Racikan favoritmu, tinggal pesan tanpa meracik ulang.</p>
        </div>
        <a href="{{ route('racik') }}" class="btn btn-matcha">+ Racik Baru</a>
    </div>

    <div class="row g-4">
        @forelse ($recipes as $recipe)
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start">
                            <h5 class="brand-text mb-1">{{ $recipe->name }}</h5>
                            <span class="badge bg-matcha-light text-matcha">{{ $recipe->size }}</span>
                        </div>
                        <ul class="small mb-2 ps-3">
                            @foreach ($recipe->ingredients as $ing)
                                <li>{{ $ing->name }}</li>
                            @endforeach
                        </ul>
                        @if ($recipe->notes)
                            <p class="small text-muted fst-italic">"{{ $recipe->notes }}"</p>
                        @endif

                        <div class="mt-auto">
                            <div class="fw-bold text-matcha mb-3">Rp {{ number_format($recipe->current_price, 0, ',', '.') }}</div>
                            <div class="d-flex gap-2">
                                <form method="POST" action="{{ route('cart.add.recipe', $recipe) }}" class="flex-grow-1">
                                    @csrf
                                    <button class="btn btn-matcha btn-sm w-100">Pesan</button>
                                </form>
                                <a href="{{ route('recipes.edit', $recipe) }}" class="btn btn-outline-matcha btn-sm">Ubah</a>
                                <form method="POST" action="{{ route('recipes.destroy', $recipe) }}"
                                      onsubmit="return confirm('Hapus racikan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center text-muted py-5">
                    Kamu belum punya racikan tersimpan.
                    <a href="{{ route('racik') }}" class="text-matcha">Mulai meracik</a>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
