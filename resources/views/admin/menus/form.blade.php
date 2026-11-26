@extends('layouts.app')
@section('title', $menu->exists ? 'Ubah Menu' : 'Tambah Menu')

@section('content')
<div class="container py-5">
    <h1 class="fw-bold brand-text mb-3">{{ $menu->exists ? 'Ubah Menu' : 'Tambah Menu' }}</h1>
    @include('admin._nav')

    <div class="card border-0 shadow-sm rounded-4" style="max-width: 720px;">
        <div class="card-body p-4">
            <form method="POST" enctype="multipart/form-data"
                  action="{{ $menu->exists ? route('admin.menus.update', $menu) : route('admin.menus.store') }}">
                @csrf
                @if ($menu->exists) @method('PUT') @endif

                <div class="mb-3">
                    <label class="form-label">Kategori</label>
                    <select name="category_id" class="form-select @error('category_id') is-invalid @enderror">
                        <option value="">-- Pilih kategori --</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id', $menu->category_id) == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    @if ($categories->isEmpty()) <div class="form-text text-danger">Buat kategori bertipe "Menu jadi" dulu.</div> @endif
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama menu</label>
                    <input type="text" name="name" value="{{ old('name', $menu->name) }}"
                           class="form-control @error('name') is-invalid @enderror">
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" rows="3"
                              class="form-control @error('description') is-invalid @enderror">{{ old('description', $menu->description) }}</textarea>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Harga (Rp)</label>
                    <input type="number" name="price" min="0" value="{{ old('price', $menu->price) }}"
                           class="form-control @error('price') is-invalid @enderror">
                    @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Gambar (jpg, png, webp, maks. 2 MB)</label>
                    @if ($menu->image)
                        <div class="mb-2"><img src="{{ $menu->image_url }}" class="rounded-3" style="height:100px" alt=""></div>
                    @endif
                    <input type="file" name="image" accept="image/*"
                           class="form-control @error('image') is-invalid @enderror">
                    @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    @if ($menu->image) <div class="form-text">Kosongkan jika tidak ingin mengganti gambar.</div> @endif
                </div>

                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" name="is_available" value="1" id="is_available"
                           @checked(old('is_available', $menu->is_available))>
                    <label class="form-check-label" for="is_available">Tersedia</label>
                </div>

                <button class="btn btn-matcha">Simpan</button>
                <a href="{{ route('admin.menus.index') }}" class="btn btn-outline-matcha">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection
