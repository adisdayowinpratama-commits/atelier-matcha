@extends('layouts.app')
@section('title', $recipe ? 'Ubah Racikan' : 'Racik Matcha')

@section('content')
@php
    $selected = collect(\Illuminate\Support\Arr::flatten(
        old('pick', $recipe ? $recipe->ingredients->pluck('id')->all() : [])
    ))->map(fn ($v) => (int) $v)->all();
    $currentSize = old('size', $recipe->size ?? 'Regular');
@endphp

<div class="container py-5">
    <h1 class="fw-bold brand-text">{{ $recipe ? 'Ubah Racikan' : 'Racik Matcha' }}</h1>
    <p class="text-muted">Pilih bahan favoritmu, harga dihitung otomatis.</p>

    @if ($errors->has('pick'))
        <div class="alert alert-danger">{{ $errors->first('pick') }}</div>
    @endif

    <form method="POST" id="racikForm"
          action="{{ $recipe ? route('recipes.update', $recipe) : route('recipes.store') }}">
        @csrf
        @if ($recipe) @method('PUT') @endif

        <div class="row g-4">
            <div class="col-lg-8">
                @foreach ($categories as $category)
                    <div class="card border-0 shadow-sm rounded-4 mb-3">
                        <div class="card-body">
                            <h5 class="brand-text mb-1">
                                {{ $loop->iteration }}. {{ $category->name }}
                                <small class="text-muted fw-normal">
                                    {{ $category->allow_multiple ? '(boleh lebih dari satu, opsional)' : '(pilih satu)' }}
                                </small>
                            </h5>
                            @if ($errors->has('pick.' . $category->id))
                                <div class="text-danger small mb-2">{{ $errors->first('pick.' . $category->id) }}</div>
                            @endif

                            <div class="row g-2 mt-1">
                                @foreach ($category->ingredients as $ing)
                                    <div class="col-12 col-md-6">
                                        <input type="{{ $category->allow_multiple ? 'checkbox' : 'radio' }}"
                                               class="btn-check"
                                               name="pick[{{ $category->id }}]{{ $category->allow_multiple ? '[]' : '' }}"
                                               id="ing{{ $ing->id }}"
                                               value="{{ $ing->id }}"
                                               data-price="{{ $ing->price }}"
                                               data-name="{{ $ing->name }}"
                                               @checked(in_array($ing->id, $selected))>
                                        <label class="btn btn-outline-matcha w-100 text-start d-flex align-items-center gap-2 p-2"
                                               for="ing{{ $ing->id }}">
                                            <img src="{{ $ing->image_url }}" class="thumb" alt="">
                                            <span class="flex-grow-1">
                                                <strong class="d-block">{{ $ing->name }}</strong>
                                                <small style="opacity:.85">{{ \Illuminate\Support\Str::limit($ing->description, 45) }}</small>
                                            </span>
                                            <span class="small fw-semibold text-nowrap">
                                                {{ $ing->price > 0 ? '+Rp ' . number_format($ing->price, 0, ',', '.') : 'Gratis' }}
                                            </span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4" style="position: sticky; top: 90px;">
                    <div class="card-body">
                        <h5 class="brand-text">Racikanmu</h5>

                        <div class="mb-3">
                            <label class="form-label">Nama racikan</label>
                            <input type="text" name="name" value="{{ old('name', $recipe->name ?? '') }}"
                                   placeholder="mis. Racikan Pagi"
                                   class="form-control @error('name') is-invalid @enderror">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ukuran</label>
                            <select name="size" class="form-select @error('size') is-invalid @enderror">
                                @foreach (\App\Models\Recipe::SIZES as $label => $extra)
                                    <option value="{{ $label }}" @selected($currentSize === $label)>
                                        {{ $label }}{{ $extra ? ' (+Rp ' . number_format($extra, 0, ',', '.') . ')' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('size') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Catatan (opsional)</label>
                            <textarea name="notes" rows="2"
                                      class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $recipe->notes ?? '') }}</textarea>
                            @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="bg-matcha-light rounded-3 p-3 mb-3">
                            <div class="small text-muted">Bahan terpilih</div>
                            <div id="summary" class="small mb-2">Belum ada bahan dipilih</div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Total</span>
                                <strong class="fs-5 text-matcha" id="totalPrice">Rp 0</strong>
                            </div>
                        </div>

                        @guest
                            <a href="{{ route('login') }}" class="btn btn-matcha w-100">Masuk untuk menyimpan / memesan</a>
                            <p class="small text-muted text-center mt-2 mb-0">Kamu bisa mencoba meracik dulu tanpa login.</p>
                        @else
                            @if ($recipe)
                                <button class="btn btn-matcha w-100 mb-2">Perbarui Racikan</button>
                                <a href="{{ route('recipes.index') }}" class="btn btn-outline-matcha w-100">Batal</a>
                            @else
                                <button type="submit" name="action" value="order" class="btn btn-matcha w-100 mb-2">Simpan &amp; Pesan</button>
                                <button type="submit" name="action" value="save" class="btn btn-outline-matcha w-100">Simpan Racikan Saja</button>
                            @endif
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const form = document.getElementById('racikForm');
    const sizes = @json(\App\Models\Recipe::SIZES);
    const rupiah = n => 'Rp ' + n.toLocaleString('id-ID');

    function update() {
        let total = sizes[form.querySelector('[name=size]').value] || 0;
        const names = [];
        form.querySelectorAll('input[data-price]:checked').forEach(function (input) {
            total += parseInt(input.dataset.price, 10);
            names.push(input.dataset.name);
        });
        document.getElementById('totalPrice').textContent = rupiah(total);
        document.getElementById('summary').textContent = names.length ? names.join(', ') : 'Belum ada bahan dipilih';
    }

    form.addEventListener('change', update);
    update();
})();
</script>
@endpush
