@extends('layouts.app')
@section('title', 'Beranda')

@section('content')
<section class="hero py-5">
    <div class="container py-4">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <span class="badge bg-matcha-light text-matcha mb-3">Craft Your Own Matcha</span>
                <h1 class="display-5 fw-bold brand-text">Racik matcha sesuai selera kamu, di Atelier Matcha.</h1>
                <p class="lead mt-3">Pilih menu matcha favorit kami, atau jadi artisan-nya sendiri: tentukan grade matcha, susu, pemanis, dan topping, lalu simpan racikan terbaikmu.</p>
                <a href="{{ route('menu.index') }}" class="btn btn-matcha btn-lg mt-2">Lihat Menu</a>
                <a href="{{ route('racik') }}" class="btn btn-outline-matcha btn-lg mt-2 ms-1">Racik Sendiri</a>
            </div>
            <div class="col-lg-5 text-center">
                @include('partials.logo', ['size' => 220])
            </div>
        </div>
    </div>
</section>

<section class="container py-5">
    <div class="row justify-content-center text-center">
        <div class="col-lg-8">
            <h2 class="fw-bold brand-text">Tentang Atelier Matcha</h2>
            <p class="mt-3">Atelier Matcha adalah usaha minuman matcha berskala UMKM yang menghadirkan pengalaman minum matcha yang personal. Seperti sebuah studio racikan, setiap pelanggan bebas memadukan matcha, susu, pemanis, dan topping sesuai selera. Bagi yang ingin praktis, kami juga menyediakan beragam menu matcha siap pesan. Racikan favorit bisa disimpan, jadi tinggal pesan lagi kapan saja tanpa meracik dari awal.</p>
        </div>
    </div>
</section>

<section class="container pb-4">
    <div class="d-flex justify-content-between align-items-end mb-3">
        <h3 class="fw-bold brand-text mb-0">Menu Terbaru</h3>
        <a href="{{ route('menu.index') }}" class="text-matcha">Lihat semua &rarr;</a>
    </div>
    <div class="row g-4">
        @forelse ($featured as $menu)
            <div class="col-12 col-sm-6 col-lg-3">
                @include('menu._card', ['menu' => $menu])
            </div>
        @empty
            <p class="text-muted">Belum ada menu.</p>
        @endforelse
    </div>
</section>
@endsection