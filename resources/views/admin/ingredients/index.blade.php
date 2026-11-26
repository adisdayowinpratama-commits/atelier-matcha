@extends('layouts.app')
@section('title', 'Kelola Bahan Racik')

@section('content')
<div class="container py-5">
    <h1 class="fw-bold brand-text mb-3">Bahan Racik</h1>
    @include('admin._nav')

    <div class="d-flex justify-content-between flex-wrap gap-2 mb-3">
        <form method="GET" class="d-flex gap-2">
            <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari bahan...">
            <button class="btn btn-outline-matcha">Cari</button>
        </form>
        <a href="{{ route('admin.ingredients.create') }}" class="btn btn-matcha">+ Tambah Bahan</a>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light"><tr><th>Gambar</th><th>Nama</th><th>Kategori</th><th>Harga tambahan</th><th></th></tr></thead>
                <tbody>
                    @forelse ($ingredients as $ingredient)
                        <tr>
                            <td><img src="{{ $ingredient->image_url }}" class="thumb" alt=""></td>
                            <td><strong>{{ $ingredient->name }}</strong></td>
                            <td>{{ $ingredient->category->name }}</td>
                            <td>Rp {{ number_format($ingredient->price, 0, ',', '.') }}</td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('admin.ingredients.edit', $ingredient) }}" class="btn btn-outline-matcha btn-sm">Ubah</a>
                                <form method="POST" action="{{ route('admin.ingredients.destroy', $ingredient) }}" class="d-inline"
                                      onsubmit="return confirm('Hapus bahan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">Belum ada bahan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $ingredients->links() }}</div>
</div>
@endsection
