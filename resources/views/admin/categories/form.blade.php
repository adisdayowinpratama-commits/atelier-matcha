@extends('layouts.app')
@section('title', $category->exists ? 'Ubah Kategori' : 'Tambah Kategori')

@section('content')
@php $locked = $category->exists && ($category->menus()->exists() || $category->ingredients()->exists()); @endphp
<div class="container py-5">
    <h1 class="fw-bold brand-text mb-3">{{ $category->exists ? 'Ubah Kategori' : 'Tambah Kategori' }}</h1>
    @include('admin._nav')

    <div class="card border-0 shadow-sm rounded-4" style="max-width: 640px;">
        <div class="card-body p-4">
            <form method="POST"
                  action="{{ $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store') }}">
                @csrf
                @if ($category->exists) @method('PUT') @endif

                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" name="name" value="{{ old('name', $category->name) }}"
                           class="form-control @error('name') is-invalid @enderror">
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Tipe</label>
                    <select name="type" id="typeSelect" class="form-select @error('type') is-invalid @enderror" @disabled($locked)>
                        <option value="menu" @selected(old('type', $category->type) === 'menu')>Menu jadi</option>
                        <option value="ingredient" @selected(old('type', $category->type ?? 'ingredient') === 'ingredient')>Bahan racik</option>
                    </select>
                    @if ($locked) <div class="form-text">Tipe tidak bisa diubah karena kategori ini sudah berisi data.</div> @endif
                    @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-check mb-3" id="multiWrap">
                    <input class="form-check-input" type="checkbox" name="allow_multiple" value="1" id="allow_multiple"
                           @checked(old('allow_multiple', $category->allow_multiple))>
                    <label class="form-check-label" for="allow_multiple">
                        Pelanggan boleh memilih lebih dari satu (mis. topping)
                    </label>
                </div>

                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" rows="3"
                              class="form-control @error('description') is-invalid @enderror">{{ old('description', $category->description) }}</textarea>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <button class="btn btn-matcha">Simpan</button>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-matcha">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const typeSelect = document.getElementById('typeSelect');
    const multiWrap = document.getElementById('multiWrap');
    function toggleMulti() { multiWrap.style.display = typeSelect.value === 'ingredient' ? '' : 'none'; }
    typeSelect.addEventListener('change', toggleMulti);
    toggleMulti();
</script>
@endpush
