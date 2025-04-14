@extends('layouts.app')

@section('title', 'Edit Customer')

@section('content')
<div class="main-content-table">
    <section class="section">
        <div class="margin-content">
            <div class="container-sm">
                <div class="section-header">
                    <h1>Edit Customer</h1>
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
                            <form action="{{ route('customers.update', $customer->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="form-group mb-3">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" name="name" id="name" 
                                           placeholder="Enter Name" value="{{ old('name', $customer->name) }}" required>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="no_hp">Phone Number</label>
                                    <input type="text" class="form-control" name="no_hp" id="no_hp" 
                                           placeholder="Enter Phone Number" value="{{ old('no_hp', $customer->phone_number) }}">
                                </div>

                                <div class="form-group mb-3">
                                    <label for="address">Address</label>
                                    <textarea class="form-control" name="address" id="address" rows="3" 
                                              placeholder="Enter Address">{{ old('address', $customer->address) }}</textarea>
                                </div>

                                <div class="form-group mb-4">
                                    <label for="points">Points</label>
                                    <input type="number" class="form-control" name="points" id="points" 
                                           placeholder="Enter Points" value="{{ old('points', $customer->points) }}" required>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('customers.index') }}" class="btn btn-secondary">
                                        Back
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        Update Customer
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
