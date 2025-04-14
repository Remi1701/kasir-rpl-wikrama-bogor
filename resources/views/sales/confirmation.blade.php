@extends('layouts.app')

@section('title', 'Konfirmasi Penjualan')

@section('content')
<div class="main-content-table">
    <section class="section">
        <div class="margin-content">
            <div class="container-sm">
                <div class="section-header">
                    <h1>Konfirmasi Penjualan</h1>
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
                            <form action="{{ route('sales.store') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5>Produk yang Dibeli</h5>
                                        <ul class="list-group">
                                        @foreach ($items as $key => $item)
                                            <li class="list-group-item">
                                                <strong>{{ $key + 1 . '. ' . $item['name']}}</strong>
                                                <br>Harga: Rp {{ number_format($item['price'], 0, ',', '.') }}
                                                <br>Jumlah: {{ $item['quantity'] }}
                                                <br>Subtotal: Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                                            </li>
                                            <hr>
                                        @endforeach
                                                                             
                                        </ul>
                                        <h5 class="mb-3">Total: Rp {{ number_format($totalAmount, 0, ',', '.') }}</h5>
                                        <input type="hidden" name="total_amount" value="{{ $totalAmount }}">
                                        <input type="hidden" name="items_data" value="{{ json_encode($items->map(function ($item) use ($filteredStock) {
                                            return [
                                                'id' => $item->id,
                                                'name' => $item->name,
                                                'price' => $item->price,
                                                'quantity' => $filteredStock[$item->id] ?? 0,
                                                'subtotal' => $item->price * ($filteredStock[$item->id] ?? 0),
                                            ];
                                        })) }}">                                        
                                    </div>

                                    <div class="col-md-6">
                                        <h5>Informasi Customers</h5>
                                        <div class="form-group mb-3">
                                            <label for="is_customers">Customers atau Bukan</label>
                                            <select class="form-control" id="is_customers" name="is_customers" required>
                                                <option value="">Pilih</option>
                                                <option value="yes">Customers</option>
                                                <option value="no">Bukan Customers</option>
                                            </select>
                                        </div>

                                        <div class="form-group mb-3" id="customers_selection" style="display: none;">
                                            <label for="customers_phone">Pilih Customers (Berdasarkan Nomor Telepon)</label>
                                            <br>
                                            <select class="form-control select2" id="customers_phone" name="customers_id">
                                                <option value="">Pilih Customers</option>
                                                @foreach ($customers as $customers)
                                                    <option value="{{ $customers->id }}">{{ $customers->phone_number }} - {{ $customers->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="total_pay">Jumlah Bayar</label>
                                            <input type="text" class="form-control" id="total_pay" value="">
                                            <input type="hidden" id="total_pay_numeric" name="total_pay">
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('sales.create') }}" class="btn btn-secondary">Back</a>
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

@push('scripts')
<script>
    $(document).ready(function() {
        $('#customers_phone').select2({
            placeholder: "Pilih Customers",
            width: '100%',
            allowClear: true
        });

        $('#is_customers').on('change', function () {
            if ($(this).val() === "yes") {
                $('#customers_selection').fadeIn();
            } else {
                $('#customers_selection').fadeOut();
                $('#customers_phone').val(null).trigger('change');
            }
        });

        $('#total_pay').on('input', function() {
            let value = $(this).val().replace(/\D/g, '');
            $('#total_pay_numeric').val(value);
            if (value) {
                $(this).val(formatRupiah(value));
            } else {
                $(this).val('');
            }
        });

        $('form').on('submit', function() {
            let totalPay = $('#total_pay').val().replace(/\D/g, '');
            $('#total_pay_numeric').val(totalPay);
        });

        function formatRupiah(angka) {
            return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }
    });
</script>
@endpush
@endsection
