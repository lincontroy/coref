@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Vehicles</h1>
        <a href="{{ route('vehicles.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Add Vehicle
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-primary">All Vehicles</h6>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Price (KES)</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vehicles as $vehicle)
                        <tr>
                            <td>#{{ $vehicle->id }}</td>
                            <td>
                                @if($vehicle->image)
                                    <img src="{{ asset($vehicle->image) }}" alt="car" width="80">
                                @else
                                    <span class="text-muted">No image</span>
                                @endif
                            </td>
                            <td>{{ $vehicle->name }}</td>
                            <td>{{ $vehicle->description }}</td>
                            <td>{{ number_format($vehicle->price, 2) }}</td>
                            <td>{{ $vehicle->created_at->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ route('vehicles.edit', $vehicle) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('vehicles.destroy', $vehicle) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this vehicle?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center">No vehicles found</td></tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $vehicles->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
