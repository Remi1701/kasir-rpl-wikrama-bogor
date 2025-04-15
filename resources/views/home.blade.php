@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    @if (Auth::user()->role == 'admin')
        <div class="section-header" style="padding-top: 10%; padding-left: 2.5%;">
            <h1>Dashboard</h1>
        </div>

        <div class="row" style="padding-left: 2.5%; padding-right: 2.5%;">
            <!-- Total Sales -->
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-primary">
                        <i class="fas fa-coins"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Penjualan</h4>
                        </div>
                        <div class="card-body">
                            Rp{{ number_format($totalSales, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Items -->
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-success">
                        <i class="fas fa-box"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Jumlah Item</h4>
                        </div>
                        <div class="card-body">
                            {{ $totalItems }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Customers -->
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-warning">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Member Terdaftar</h4>
                        </div>
                        <div class="card-body">
                            {{ $totalCustomers }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Users -->
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-danger">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Admin/User</h4>
                        </div>
                        <div class="card-body">
                            {{ $totalUsers }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sales Chart & Top Items -->
        <div class="row" style="padding-left: 2.5%">
            <!-- Sales Chart -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Grafik Penjualan Minggu Ini</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="salesChart" height="100"></canvas>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if (Auth::user()->role == 'user')
        <div class="section-header" style="padding-top: 10%; padding-left: 2.5%;">
            <h1>Dashboard</h1>
        </div>

        <div class="row" style="padding-left: 2.5%; padding-right: 2.5%;">
            <!-- Total Sales -->
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-primary">
                        <i class="fas fa-coins"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Penjualan</h4>
                        </div>
                        <div class="card-body">
                            Rp{{ number_format($totalSales, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Sales Chart & Top Items -->
        <div class="row" style="padding-left: 2.5%">
            <!-- Sales Chart -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Grafik Penjualan Minggu Ini</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="salesChart" height="100"></canvas>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($salesChartLabels) !!},
                datasets: [{
                    label: 'Penjualan',
                    data: {!! json_encode($salesChartData) !!},
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: '#36A2EB',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3
                }]
            }
        });
    </script>
@endpush
