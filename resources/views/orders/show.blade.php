@extends('layouts.app')
@section('title', 'Pesanan #' . $order->id)

@section('content')
<div class="container py-5">
    <a href="{{ route('orders.index') }}" class="text-matcha">&larr; Semua pesanan</a>

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3 mb-4">
        <h1 class="fw-bold brand-text mb-0">Pesanan #{{ $order->id }}</h1>
        <span class="badge fs-6 {{ $order->status_class }}">{{ $order->status_label }}</span>
    </div>
    <p class="text-muted">Dibuat {{ $order->created_at->format('d M Y H:i') }}</p>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr><th>Item</th><th class="text-end">Harga</th><th class="text-center">Jumlah</th><th class="text-end">Subtotal</th></tr>
                </thead>
                <tbody>
                    @foreach ($order->items as $item)
                        <tr>
                            <td>
                                <strong>{{ $item->item_name }}</strong>
                                <div class="small text-muted">{{ $item->item_detail }}</div>
                            </td>
                            <td class="text-end">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-end">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-end">Total</th>
                        <th class="text-end text-matcha">Rp {{ number_format($order->total_price, 0, ',', '.') }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    @if ($order->notes)
        <p class="mt-3"><strong>Catatan:</strong> {{ $order->notes }}</p>
    @endif
</div>
@endsection
