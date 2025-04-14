<?php
use Illuminate\Support\Facades\DB;
?>
@extends('layouts.app')

@section('title', 'Penjualan')

@push('style')
@endpush

@section('content')
<div class="main-content-table">
    <section class="section">
        <div class="margin-content">
            <div class="container-sm">
                <div class="section-header">
                    <h1>Penjualan</h1>
                </div>
                <div class="section-body">
                    <div class="table-responsive">
                        <div class="row mb-3">
                            <div class="col-md-12 d-flex justify-content-between align-items-center">
                                <form action="{{ route('sales.index') }}" method="GET" class="d-flex"
                                style="max-width: 100%%;">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control rounded"
                                    placeholder="Search">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary rounded ml-2" type="submit">Search</button>
                                    </div>
                                </div>
                            </form>
                            @if(Auth::user()->role == 'user')
                            <a href="{{ route('sales.create') }}" class="btn btn-success ml-2 p-2">
                                Tambah Penjualan
                            </a>
                            @else 
                            <a href="{{ route('sales.export') }}" class="btn btn-success ml-2 p-2">
                                Export Excel
                            </a>                            
                            @endif
                        </div>
                    </div>
                    <table class="table table-bordered" style="background-color: #f3f3f3">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Pelanggan</th>
                                <th>Tanggal Penjualan</th>
                                <th>Total Harga</th>
                                <th>Dibuat Oleh</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                @foreach ($sales as $index => $item)
                                <td>{{ $sales->firstItem() + $index }}</td>
                                <td>{{ $item->customer_name }}</td>
                                <td>{{ $item->created_at }}</td>
                                <td>{{ 'Rp ' . number_format($item->total_amount, 0, ',', '.') }}</td>
                                <td>{{ DB::table('users')->where('id', $item->user_id)->value('name') }}</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-primary detail-transaction-btn" data-toggle="modal" data-target="#transactionDetailModal" data-transaction='{{ json_encode($item) }}'>Lihat</button>
                                    <a href="{{ route('sales.invoice', $item->id) }}" class="btn btn-primary">Unduh Bukti</a>
                                </td>                        
                            </tr>
                            @endforeach
                        </tbody>
                        </table>
                        <div class="d-flex justify-content-end mt-3">
                            {{ $sales->links() }}
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

<!-- Detail Transaksi Modal -->
<div class="modal fade" id="transactionDetailModal" tabindex="-1" aria-labelledby="transactionDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Transaksi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>Nomor Invoice:</strong> <span id="invoiceNumber"></span></p>
                <p><strong>Nama Pelanggan:</strong> <span id="customerName"></span></p>
                <p><strong>Total Bayar:</strong> Rp <span id="paymentAmount"></span></p>
                <p><strong>Total Harga:</strong> Rp <span id="totalAmount"></span></p>
                <p><strong>Potongan Harga:</strong> Rp <span id="discountAmount">0</span></p>
                <p><strong>Kembalian:</strong> Rp <span id="changeAmount"></span></p>
                <h5 class="mt-3">Produk:</h5>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="transactionProducts"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.detail-transaction-btn').forEach(button => {
        button.addEventListener('click', function () {
            const data = JSON.parse(this.getAttribute('data-transaction'));

            const transactionProducts = document.getElementById('transactionProducts');
            transactionProducts.innerHTML = '';

            let products = typeof data.product_data === "string" ? JSON.parse(data.product_data) : data.product_data;

            let totalProductPrice = 0;

            products.forEach((product, index) => {
                const subtotal = product.price * product.quantity;
                totalProductPrice += subtotal;

                transactionProducts.innerHTML += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${product.name}</td>
                        <td>Rp ${parseInt(product.price || 0).toLocaleString('id-ID')}</td>
                        <td>${product.quantity}</td>
                        <td>Rp ${subtotal.toLocaleString('id-ID')}</td>
                    </tr>`;
            });

            document.getElementById('invoiceNumber').textContent = data.invoice_number || 'N/A';
            document.getElementById('customerName').textContent = data.customer_name || 'N/A';
            document.getElementById('totalAmount').textContent = (data.total_amount || 0).toLocaleString('id-ID');
            document.getElementById('paymentAmount').textContent = (data.payment_amount || 0).toLocaleString('id-ID');
            document.getElementById('changeAmount').textContent = (data.change_amount || 0).toLocaleString('id-ID');
            document.getElementById('discountAmount').textContent = (totalProductPrice - data.total_amount || 0).toLocaleString('id-ID');
        });
    });
});
</script>
@endpush
