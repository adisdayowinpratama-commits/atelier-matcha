@extends('layouts.app')
@section('title', 'Dashboard Admin')

@section('content')
<div class="container py-5">
    <h1 class="fw-bold brand-text mb-3">Panel Admin</h1>
    @include('admin._nav')

    @php
        $stats = [
            ['Menu', $menuCount],
            ['Bahan Racik', $ingredientCount],
            ['Kategori', $categoryCount],
            ['Total Pesanan', $orderCount],
            ['Pesanan Menunggu', $pendingCount],
        ];
    @endphp
    <div class="row g-3 mb-4">
        @foreach ($stats as [$label, $value])
            <div class="col-6 col-md-4 col-lg">
                <div class="card border-0 shadow-sm rounded-4 text-center h-100">
                    <div class="card-body">
                        <div class="fs-2 fw-bold text-matcha">{{ $value }}</div>
                        <div class="small text-muted">{{ $label }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="alert bg-matcha-light">
        Pendapatan dari pesanan selesai: <strong>Rp {{ number_format($revenue, 0, ',', '.') }}</strong>
    </div>

    <h5 class="brand-text mt-4">Pesanan Terbaru</h5>
    <div class="card border-0 shadow-sm rounded-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light"><tr><th>#</th><th>Pelanggan</th><th>Total</th><th>Status</th><th></th></tr></thead>
                <tbody>
                    @forelse ($latestOrders as $order)
                        <tr>
                            <td>#{{ $order->id }}</td>
                            <td>{{ $order->user->name }}</td>
                            <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                            <td><span class="badge {{ $order->status_class }}">{{ $order->status_label }}</span></td>
                            <td class="text-end"><a href="{{ route('admin.orders.show', $order) }}" class="btn btn-outline-matcha btn-sm">Kelola</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">Belum ada pesanan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
