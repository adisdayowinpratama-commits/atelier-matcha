@extends('layouts.app')
@section('title', 'Kelola Kategori')

@section('content')
<div class="container py-5">
    <h1 class="fw-bold brand-text mb-3">Kategori</h1>
    @include('admin._nav')

    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('admin.categories.create') }}" class="btn btn-matcha">+ Tambah Kategori</a>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light"><tr><th>Nama</th><th>Tipe</th><th>Isi</th><th>Deskripsi</th><th></th></tr></thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr>
                            <td><strong>{{ $category->name }}</strong></td>
                            <td>
                                <span class="badge {{ $category->type === 'menu' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $category->type === 'menu' ? 'Menu' : 'Bahan racik' }}
                                </span>
                                @if ($category->allow_multiple) <span class="badge bg-info text-dark">Multi-pilih</span> @endif
                            </td>
                            <td>{{ $category->type === 'menu' ? $category->menus_count . ' menu' : $category->ingredients_count . ' bahan' }}</td>
                            <td class="small text-muted">{{ \Illuminate\Support\Str::limit($category->description, 50) }}</td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-outline-matcha btn-sm">Ubah</a>
                                <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="d-inline"
                                      onsubmit="return confirm('Hapus kategori ini beserta seluruh isinya?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">Belum ada kategori.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $categories->links() }}</div>
</div>
@endsection
