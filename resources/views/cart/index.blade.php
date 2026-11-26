@extends('layouts.app')
@section('title', 'Keranjang')

@section('content')
<div class="container py-5">
    <h1 class="fw-bold brand-text mb-4">Keranjang</h1>

    @if (empty($lines))
        <div class="text-center text-muted py-5">
            Keranjangmu masih kosong.
            <a href="{{ route('menu.index') }}" class="text-matcha">Lihat menu</a> atau
            <a href="{{ route('racik') }}" class="text-matcha">racik sendiri</a>.
        </div>
    @else
        <div class="row g-4">
            <div class="col-lg-8">
                @foreach ($lines as $line)
                    <div class="card border-0 shadow-sm rounded-4 mb-3">
                        <div class="card-body d-flex gap-3 align-items-center flex-wrap">
                            <img src="{{ $line['image'] }}" class="rounded-3" style="width:72px;height:72px;object-fit:cover" alt="">
                            <div class="flex-grow-1">
                                <strong>{{ $line['name'] }}</strong>
                                <div class="small text-muted">{{ $line['detail'] }}</div>
                                <div class="small">Rp {{ number_format($line['price'], 0, ',', '.') }}</div>
                            </div>
                            <form method="POST" action="{{ route('cart.update', $line['key']) }}" class="d-flex gap-1">
                                @csrf
                                @method('PATCH')
                                <input type="number" name="quantity" value="{{ $line['qty'] }}" min="1" max="20"
                                       class="form-control form-control-sm" style="width:70px">
                                <button class="btn btn-outline-matcha btn-sm">Ubah</button>
                            </form>
                            <div class="fw-bold text-matcha text-end" style="min-width:100px">
                                Rp {{ number_format($line['subtotal'], 0, ',', '.') }}
                            </div>
                            <form method="POST" action="{{ route('cart.remove', $line['key']) }}">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-outline-danger btn-sm">Hapus</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body">
                        <h5 class="brand-text">Ringkasan</h5>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Total</span>
                            <strong class="fs-5 text-matcha">Rp {{ number_format($total, 0, ',', '.') }}</strong>
                        </div>
                        <form method="POST" action="{{ route('cart.checkout') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Catatan pesanan (opsional)</label>
                                <textarea name="notes" rows="2"
                                          class="form-control @error('notes') is-invalid @enderror">{{ old('notes') }}</textarea>
                                @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <button class="btn btn-matcha w-100">Pesan Sekarang</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
