@extends('layouts.admin')

@section('content')
<div class="container">
    <h3>Edit Vehicle</h3>

    <form action="{{ route('vehicles.update', $vehicle->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required value="{{ old('name', $vehicle->name) }}">
        </div>

        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="4" required>{{ old('description', $vehicle->description) }}</textarea>
        </div>
        
        <div class="mb-3">
            <label>Price (KES)</label>
            <input type="number" name="price" class="form-control" required value="{{ old('price', $vehicle->price) }}">
        </div>

        <div class="mb-3">
            <label>Current Vehicle Image</label><br>
            @if($vehicle->image)
                <img src="{{ asset($vehicle->image) }}" alt="Vehicle Image" width="150" class="mb-2">
            @else
                <p>No image uploaded</p>
            @endif
        </div>

        <div class="mb-3">
            <label>Change Vehicle Image</label>
            <input type="file" name="image" class="form-control">
            <small class="text-muted">Leave blank to keep current image</small>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('vehicles.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
