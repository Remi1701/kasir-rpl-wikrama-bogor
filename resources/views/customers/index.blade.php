@extends('layouts.app')

@section('title', 'Customers')

@push('style')
<!-- Add any custom styles if needed -->
@endpush

@section('content')
<div class="main-content-table">
    <section class="section">
        <div class="margin-content">
            <div class="container-sm">
                <div class="section-header">
                    <h1>Customers</h1>
                </div>

                <div class="section-body">
                    <div class="table-responsive">
                        <div class="row mb-3">
                            <div class="col-md-12 d-flex justify-content-between align-items-center">
                                <form action="{{ route('customers.index') }}" method="GET" class="d-flex" style="max-width: 100%;">
                                    <div class="input-group">
                                        <input type="text" name="search" class="form-control rounded" placeholder="Search Customer">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary rounded ml-2" type="submit">Search</button>
                                        </div>
                                    </div>
                                </form>
                                <a href="{{ route('customers.create') }}" class="btn btn-success ml-2 p-2">
                                    Add Customer
                                </a>
                            </div>
                        </div>

                        <table class="table table-bordered my-3" style="background-color: #f3f3f3">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Points</th>
                                    <th>Phone Number</th>
                                    @if(Auth::user()->role == 'admin')
                                    <th>Action</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($customers as $index => $customer)
                                <tr>
                                    <td>{{ $customers->firstItem() + $index }}</td>
                                    <td>{{ $customer->name }}</td>
                                    <td>{{ $customer->points }}</td>
                                    <td>{{ $customer->no_hp ?? '-' }}</td>
                                    @if(Auth::user()->role == 'admin')
                                    <td class="text-center">
                                        <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                        <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" class="delete-form d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </form> 
                                    </td>
                                    @endif
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">No customers found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <div class="d-flex justify-content-center mt-3">
                            {{ $customers->links() }}
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
    document.querySelectorAll('form.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "Customer will be permanently deleted!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete!'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    });

    @if(session('message'))
        Swal.fire({
            title: 'Success!',
            text: '{{ session('message') }}',
            icon: 'success',
            timer: 2000,
            showConfirmButton: false,
        });
    @endif

    @if(session('error'))
        Swal.fire({
            title: 'Error!',
            text: '{{ session('error') }}',
            icon: 'error',
            timer: 3000,
            showConfirmButton: false,
        });
    @endif
</script>
@endpush
