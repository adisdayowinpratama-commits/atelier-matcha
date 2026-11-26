@extends('layouts.app')
@section('title', 'Pesanan #' . $order->id)

@section('content')
<div class="container py-5">
    <h1 class="fw-bold brand-text mb-3">Pesanan #{{ $order->id }}</h1>
    @include('admin._nav')

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light"><tr><th>Item</th><th class="text-end">Harga</th><th class="text-center">Jumlah</th><th class="text-end">Subtotal</th></tr></thead>
                        <tbody>
                            @foreach ($order->items as $item)
                                <tr>
                                    <td><strong>{{ $item->item_name }}</strong><div class="small text-muted">{{ $item->item_detail }}</div></td>
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
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <p class="mb-1"><strong>Pelanggan:</strong> {{ $order->user->name }}</p>
                    <p class="mb-1 small text-muted">{{ $order->user->email }}</p>
                    <p class="small text-muted">{{ $order->created_at->format('d M Y H:i') }}</p>
                    @if ($order->notes) <p><strong>Catatan:</strong> {{ $order->notes }}</p> @endif

                    <form method="POST" action="{{ route('admin.orders.update', $order) }}">
                        @csrf
                        @method('PATCH')
                        <label class="form-label">Status pesanan</label>
                        <select name="status" class="form-select mb-3 @error('status') is-invalid @enderror">
                            @foreach (\App\Models\Order::STATUSES as $value => $label)
                                <option value="{{ $value }}" @selected($order->status === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('status') <div class="invalid-feedback d-block mb-2">{{ $message }}</div> @enderror
                        <button class="btn btn-matcha w-100">Perbarui Status</button>
                    </form>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-matcha w-100 mt-2">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
