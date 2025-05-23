<?php
use Illuminate\Support\Facades\DB;
?>
@extends('layouts.app')

@section('title', 'Sales')

@push('style')
@endpush

@section('content')
<div class="main-content-table">
    <section class="section">
        <div class="margin-content">
            <div class="container-sm">
                <div class="section-header">
                    <h1>Sales</h1>
                </div>
                <div class="section-body">
                    <div class="table-responsive">
                        <div class="row mb-3">
                            <div class="col-md-12 d-flex justify-content-between align-items-center">
                                <form action="{{ route('sales.index') }}" method="GET" class="d-flex" style="max-width: 100%;">
                                    <div class="input-group">
                                        <input type="text" name="search" class="form-control rounded" placeholder="Search" value="{{ request()->search }}">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary rounded ml-2" type="submit">Search</button>
                                        </div>
                                    </div>
                                    <select name="filter_type" class="form-control rounded ml-2" id="filter_type">
                                        <option value="">Select Filter</option>
                                        <option value="daily" {{ request()->filter_type == 'daily' ? 'selected' : '' }}>Daily</option>
                                        <option value="weekly" {{ request()->filter_type == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                        <option value="monthly" {{ request()->filter_type == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                        <option value="yearly" {{ request()->filter_type == 'yearly' ? 'selected' : '' }}>Yearly</option>
                                    </select>
                                    <div class="ml-2" id="date-input">
                                        @if(request()->filter_type == 'daily')
                                            <input type="date" name="date" class="form-control rounded" value="{{ request()->date }}">
                                        @elseif(request()->filter_type == 'weekly')
                                            <input type="week" name="week" class="form-control rounded" value="{{ request()->week }}">
                                        @elseif(request()->filter_type == 'monthly')
                                            <input type="month" name="month" class="form-control rounded" value="{{ request()->month }}">
                                        @elseif(request()->filter_type == 'yearly')
                                            <input type="number" name="year" class="form-control rounded" value="{{ request()->year }}" placeholder="Year (e.g., 2023)">
                                        @endif
                                    </div>
                                    <div class="input-group-append">
                                        <button class="btn btn-primary rounded ml-2" type="submit">Filter</button>
                                    </div>
                                </form>
                                @if(Auth::user()->role == 'user')
                                <a href="{{ route('sales.create') }}" class="btn btn-success ml-2 p-2">Tambah Sales</a>
                                @else
                                <form action="{{ route('sales.exportAll') }}" method="GET">
                                    <input type="hidden" name="filter_type" value="{{ request('filter_type') }}">
                                    <input type="hidden" name="date" value="{{ request('date') }}">
                                    <input type="hidden" name="week" value="{{ request('week') }}">
                                    <input type="hidden" name="month" value="{{ request('month') }}">
                                    <input type="hidden" name="year" value="{{ request('year') }}">
                                    <button class="btn btn-success ml-2 p-2" type="submit">Export Excel</button>
                                </form>
                                @endif
                            </div>
                        </div>
                    <table class="table table-bordered" style="background-color: #f3f3f3">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nomor Invoice</th>
                                <th>Tanggal Sales</th>
                                <th>Total Harga</th>
                                <th>Dibuat Oleh</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                @foreach ($sales as $index => $items)
                                <td>{{ $sales->firstItem() + $index }}</td>
                                <td>{{ $items->invoice_number }}</td>
                                <td>{{ $items->created_at->format('d-m-Y H:i') }}</td>
                                <td>{{ 'Rp ' . number_format($items->total_amount, 0, ',', '.') }}</td>
                                <td>{{ DB::table('users')->where('id', $items->user_id)->value('name') }}</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-primary detail-transaction-btn" data-toggle="modal" data-target="#transactionDetailModal" data-transaction='{{ json_encode($items) }}'>Lihat</button>
                                    <a href="{{ route('sales.invoice', $items->id) }}" class="btn btn-primary">Unduh Bukti</a>
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
                        <tbody id="transactionItems"></tbody>
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

            const transactionItems = document.getElementById('transactionItems');
            transactionItems.innerHTML = '';

            let items = typeof data.items_data === "string" ? JSON.parse(data.items_data) : data.items_data;

            let totalItemsPrice = 0;

            items.forEach((items, index) => {
                const subtotal = items.price * items.stock;
                totalItemsPrice += subtotal;

                transactionItems.innerHTML += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${items.name}</td>
                        <td>Rp ${parseInt(items.price || 0).toLocaleString('id-ID')}</td>
                        <td>${items.stock}</td>
                        <td>Rp ${subtotal.toLocaleString('id-ID')}</td>
                    </tr>`;
            });

            document.getElementById('invoiceNumber').textContent = data.invoice_number || 'N/A';
            document.getElementById('customerName').textContent = data.customer_name || 'N/A';
            document.getElementById('totalAmount').textContent = (data.total_amount || 0).toLocaleString('id-ID');
            document.getElementById('paymentAmount').textContent = (data.payment_amount || 0).toLocaleString('id-ID');
            document.getElementById('changeAmount').textContent = (data.change_amount || 0).toLocaleString('id-ID');
            document.getElementById('discountAmount').textContent = (totalItemsPrice - data.total_amount || 0).toLocaleString('id-ID');
        });
    });
});
document.getElementById('filter_type').addEventListener('change', function() {
    const selectedFilter = this.value;
    const dateInputDiv = document.getElementById('date-input');
    dateInputDiv.innerHTML = '';
    if (selectedFilter == 'daily') {
        dateInputDiv.innerHTML = '<input type="date" name="date" class="form-control rounded">';
    } else if (selectedFilter == 'weekly') {
        dateInputDiv.innerHTML = '<input type="week" name="week" class="form-control rounded">';
    } else if (selectedFilter == 'monthly') {
        dateInputDiv.innerHTML = '<input type="month" name="month" class="form-control rounded">';
    } else if (selectedFilter == 'yearly') {
        dateInputDiv.innerHTML = '<input type="number" name="year" class="form-control rounded" placeholder="Year (e.g., 2023)">';
    }
});
</script>
@endpush
