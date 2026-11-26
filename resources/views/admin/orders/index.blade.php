@extends('layouts.app')
@section('title', 'Kelola Pesanan')

@section('content')
<div class="container py-5">
    <h1 class="fw-bold brand-text mb-3">Pesanan Masuk</h1>
    @include('admin._nav')

    <form method="GET" class="d-flex gap-2 mb-3" style="max-width: 320px;">
        <select name="status" class="form-select" onchange="this.form.submit()">
            <option value="">Semua status</option>
            @foreach (\App\Models\Order::STATUSES as $value => $label)
                <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </form>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light"><tr><th>#</th><th>Tanggal</th><th>Pelanggan</th><th>Item</th><th>Total</th><th>Status</th><th></th></tr></thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            <td>#{{ $order->id }}</td>
                            <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                            <td>{{ $order->user->name }}</td>
                            <td>{{ $order->items_count }}</td>
                            <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                            <td><span class="badge {{ $order->status_class }}">{{ $order->status_label }}</span></td>
                            <td class="text-end"><a href="{{ route('admin.orders.show', $order) }}" class="btn btn-outline-matcha btn-sm">Kelola</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">Belum ada pesanan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $orders->links() }}</div>
</div>
@endsection
