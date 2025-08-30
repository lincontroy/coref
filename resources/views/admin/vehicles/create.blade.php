@extends('layouts.admin')

@section('content')
<div class="container">
    <h3>Add Vehicle</h3>

    <form action="{{ route('vehicles.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
        </div>

        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="4" required>{{ old('description') }}</textarea>
        </div>
        

        <div class="mb-3">
            <label>Price (KES)</label>
            <input type="number" name="price" class="form-control" required value="{{ old('price') }}">
        </div>

        <div class="mb-3">
            <label>Vehicle Image</label>
            <input type="file" name="image" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Save</button>
        <a href="{{ route('vehicles.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
