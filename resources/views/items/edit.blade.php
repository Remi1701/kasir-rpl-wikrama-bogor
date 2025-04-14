@extends('layouts.app')

@section('title', 'Edit Item')

@push('style')
<style>
    .custom-file-wrapper {
        display: flex;
        align-items: center;
        gap: 10px;
    }
</style>
@endpush

@section('content')
<div class="main-content-table">
    <section class="section">
        <div class="margin-content">
            <div class="container-sm">
                <div class="section-header">
                    <h1>Edit Item</h1>
                </div>

                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="section-body">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <form action="{{ route('items.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="form-group mb-3">
                                    <label for="name">Nama Produk</label>
                                    <input type="text" class="form-control" name="name" id="name" placeholder="Nama Produk" value="{{ old('name', $item->name) }}" required>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="price">Harga</label>
                                    <input type="text" class="form-control" name="price_display" id="price_display" placeholder="Harga Produk" value="{{ old('price', $item->price) }}" required>
                                    <input type="hidden" name="price" id="price" value="{{ old('price', $item->price) }}">
                                </div>

                                <div class="form-group mb-3">
                                    <label for="stock">Stock</label>
                                    <input type="number" class="form-control" name="stock" id="stock" placeholder="Jumlah Stok" value="{{ old('stock', $item->stock) }}" required>
                                </div>

                                <div class="form-group mb-4">
                                    <label for="image">Gambar Produk</label>
                                    <div class="custom-file-wrapper">
                                        <label for="image" class="btn btn-primary">
                                            Pilih Gambar
                                        </label>
                                        <span id="file-name" class="ms-2 text-muted">Belum ada file dipilih</span>
                                        <input type="file" name="image" id="image" accept="image/*" style="display: none;">
                                    </div>

                                    <div class="mt-3">
                                        <img id="imagePreview" src="{{ asset('storage/' . $item->image) }}" alt="Preview Gambar" style="max-width: 200px; max-height: 200px; object-fit: cover;" class="rounded shadow-sm border">
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('items.index') }}" class="btn btn-secondary">
                                        Back
                                    </a>
                                    <button type="submit" class="btn btn-success">
                                        Update Item
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const priceDisplay = document.getElementById('price_display');
        const priceHidden = document.getElementById('price');
        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('imagePreview');
        const fileNameSpan = document.getElementById('file-name');

        function formatRupiah(value) {
            const number_string = value.replace(/[^,\d]/g, '').toString();
            const split = number_string.split(',');
            let sisa = split[0].length % 3;
            let rupiah = split[0].substr(0, sisa);
            const ribuan = split[0].substr(sisa).match(/\d{3}/g);

            if (ribuan) {
                const separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            return 'Rp ' + rupiah + (split[1] !== undefined ? ',' + split[1] : '');
        }

        // Initialize price formatting
        if (priceDisplay.value) {
            priceDisplay.value = formatRupiah(priceDisplay.value);
            priceHidden.value = priceDisplay.value.replace(/[^,\d]/g, '').replace('.', '');
        }

        priceDisplay.addEventListener('input', function () {
            const formatted = formatRupiah(this.value);
            this.value = formatted;

            const raw = this.value.replace(/[^,\d]/g, '').replace('.', '');
            priceHidden.value = raw;
        });

        imageInput.addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block';
                };
                reader.readAsDataURL(file);
                fileNameSpan.textContent = file.name;
            } else {
                fileNameSpan.textContent = 'Belum ada file dipilih';
            }
        });
    });
</script>
@endpush
