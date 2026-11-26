@extends('layouts.app')
@section('title', 'Kelola Menu')

@section('content')
<div class="container py-5">
    <h1 class="fw-bold brand-text mb-3">Menu</h1>
    @include('admin._nav')

    <div class="d-flex justify-content-between flex-wrap gap-2 mb-3">
        <form method="GET" class="d-flex gap-2">
            <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari menu...">
            <button class="btn btn-outline-matcha">Cari</button>
        </form>
        <a href="{{ route('admin.menus.create') }}" class="btn btn-matcha">+ Tambah Menu</a>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light"><tr><th>Gambar</th><th>Nama</th><th>Kategori</th><th>Harga</th><th>Status</th><th></th></tr></thead>
                <tbody>
                    @forelse ($menus as $menu)
                        <tr>
                            <td><img src="{{ $menu->image_url }}" class="thumb" alt=""></td>
                            <td><strong>{{ $menu->name }}</strong></td>
                            <td>{{ $menu->category->name }}</td>
                            <td>Rp {{ number_format($menu->price, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge {{ $menu->is_available ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $menu->is_available ? 'Tersedia' : 'Habis' }}
                                </span>
                            </td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('admin.menus.edit', $menu) }}" class="btn btn-outline-matcha btn-sm">Ubah</a>
                                <form method="POST" action="{{ route('admin.menus.destroy', $menu) }}" class="d-inline"
                                      onsubmit="return confirm('Hapus menu ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">Belum ada menu.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $menus->links() }}</div>
</div>
@endsection
