@extends('layouts.app')

@section('title', 'Tambah Penjualan')

@section('content')
<div class="main-content-table">
    <section class="section">
        <div class="margin-content">
            <div class="container-sm">
                <div class="section-header">
                    <h1>Tambah Penjualan</h1>
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
                            <form action="{{ route('sales.confirmationStore') }}" method="POST">
                                @csrf
                                <div class="row">
                                    @foreach ($products as $product)
                                        <div class="col-md-4 d-flex align-items-stretch">
                                            <div class="card mb-3 w-100 d-flex flex-column">
                                                <div class="d-flex justify-content-center p-3" style="height: 250px; overflow: hidden;">
                                                    <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="object-fit: cover; height: 100%; width: 100%; max-height: 180px;">
                                                </div>
                                                <div class="card-body d-flex flex-column flex-grow-1 justify-content-between">
                                                    <h5 class="card-title text-center">{{ $product->name }}</h5>
                                                    <p class="card-text text-center">Harga: Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                                    <p class="card-text text-center">Stok: {{ $product->quantity }}</p>
                                                    <div class="d-flex justify-content-center align-items-center">
                                                        <button type="button" class="btn btn-sm btn-outline-secondary decrement" data-id="{{ $product->id }}">-</button>
                                                        <input type="number" name="quantities[{{ $product->id }}]" id="quantity-{{ $product->id }}" class="form-control text-center mx-2" style="width: 60px;" min="0" value="0">
                                                        <button type="button" class="btn btn-sm btn-outline-secondary increment" data-id="{{ $product->id }}">+</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('sales.index') }}" class="btn btn-secondary">Kembali</a>
                                    <button type="submit" class="btn btn-primary">Tambah Penjualan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".increment").forEach(button => {
            button.addEventListener("click", function () {
                let productId = this.getAttribute("data-id");
                let input = document.getElementById("quantity-" + productId);
                if (input) {
                    input.value = parseInt(input.value) + 1;
                }
            });
        });

        document.querySelectorAll(".decrement").forEach(button => {
            button.addEventListener("click", function () {
                let productId = this.getAttribute("data-id");
                let input = document.getElementById("quantity-" + productId);
                if (input && parseInt(input.value) > 0) {
                    input.value = parseInt(input.value) - 1;
                }
            });
        });
    });
</script>

@endsection