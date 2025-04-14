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
                                    @foreach ($items as $item)
                                        <div class="col-md-4 d-flex align-items-stretch">
                                            <div class="card mb-3 w-100 d-flex flex-column">
                                                <div class="d-flex justify-content-center p-3" style="height: 250px; overflow: hidden;">
                                                    <img src="{{ asset('storage/' . $item->image) }}" class="card-img-top" alt="{{ $item->name }}">
                                                </div>
                                                <div class="card-body d-flex flex-column flex-grow-1 justify-content-between">
                                                    <h5 class="card-title text-center">{{ $item->name }}</h5>
                                                    <p class="card-text text-center">Harga: Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                                    <p class="card-text text-center">Stok: {{ $item->stock }}</p>
                                                    <div class="d-flex justify-content-center align-items-center">
                                                        <button type="button" class="btn btn-sm btn-outline-secondary decrement" data-id="{{ $item->id }}">-</button>
                                                        <input type="number" name="stock[{{ $item->id }}]" id="stock-{{ $item->id }}" class="form-control mx-2" value="0" min="0" max="{{ $item->stock }}" data-stock="{{ $item->stock }}" required>                      
                                                        <button type="button" class="btn btn-sm btn-outline-secondary increment" data-id="{{ $item->id }}">+</button>
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
                let itemsId = this.getAttribute("data-id");
                let input = document.getElementById("stock-" + itemsId);
                let currentVal = parseInt(input.value) || 0;
                let stock = parseInt(input.getAttribute("data-stock")) || 0;
                if (currentVal < stock) {
                    input.value = currentVal + 1;
                }
            });
        });
    
        document.querySelectorAll(".decrement").forEach(button => {
            button.addEventListener("click", function () {
                let itemsId = this.getAttribute("data-id");
                let input = document.getElementById("stock-" + itemsId);
                let currentVal = parseInt(input.value) || 0;
                if (currentVal > 0) {
                    input.value = currentVal - 1;
                }
            });
        });
    });
</script>
@endsection