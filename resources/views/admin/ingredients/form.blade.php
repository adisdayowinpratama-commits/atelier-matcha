@extends('layouts.app')
@section('title', $ingredient->exists ? 'Ubah Bahan' : 'Tambah Bahan')

@section('content')
<div class="container py-5">
    <h1 class="fw-bold brand-text mb-3">{{ $ingredient->exists ? 'Ubah Bahan' : 'Tambah Bahan' }}</h1>
    @include('admin._nav')

    <div class="card border-0 shadow-sm rounded-4" style="max-width: 720px;">
        <div class="card-body p-4">
            <form method="POST" enctype="multipart/form-data"
                  action="{{ $ingredient->exists ? route('admin.ingredients.update', $ingredient) : route('admin.ingredients.store') }}">
                @csrf
                @if ($ingredient->exists) @method('PUT') @endif

                <div class="mb-3">
                    <label class="form-label">Kategori</label>
                    <select name="category_id" class="form-select @error('category_id') is-invalid @enderror">
                        <option value="">-- Pilih kategori --</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id', $ingredient->category_id) == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    @if ($categories->isEmpty()) <div class="form-text text-danger">Buat kategori bertipe "Bahan racik" dulu.</div> @endif
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama bahan</label>
                    <input type="text" name="name" value="{{ old('name', $ingredient->name) }}"
                           class="form-control @error('name') is-invalid @enderror">
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" rows="2"
                              class="form-control @error('description') is-invalid @enderror">{{ old('description', $ingredient->description) }}</textarea>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Harga tambahan (Rp)</label>
                    <input type="number" name="price" min="0" value="{{ old('price', $ingredient->price ?? 0) }}"
                           class="form-control @error('price') is-invalid @enderror">
                    @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label">Gambar (jpg, png, webp, maks. 2 MB)</label>
                    @if ($ingredient->image)
                        <div class="mb-2"><img src="{{ $ingredient->image_url }}" class="rounded-3" style="height:100px" alt=""></div>
                    @endif
                    <input type="file" name="image" accept="image/*"
                           class="form-control @error('image') is-invalid @enderror">
                    @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    @if ($ingredient->image) <div class="form-text">Kosongkan jika tidak ingin mengganti gambar.</div> @endif
                </div>

                <button class="btn btn-matcha">Simpan</button>
                <a href="{{ route('admin.ingredients.index') }}" class="btn btn-outline-matcha">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection
