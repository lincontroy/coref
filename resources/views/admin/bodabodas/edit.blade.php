@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Edit BodaBoda</h1>
    <form action="{{ route('admin.bodabodas.update', $bodaboda->id) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')

        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required value="{{ old('name', $bodaboda->name) }}">
        </div>

        <div class="mb-3">
            <label>Price</label>
            <input type="number" step="0.01" name="price" class="form-control" required value="{{ old('price', $bodaboda->price) }}">
        </div>

        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control">{{ old('description', $bodaboda->description) }}</textarea>
        </div>

        <div class="mb-3">
            <label>Image</label><br>
            @if($bodaboda->image)
                <img src="{{ asset('storage/'.$bodaboda->image) }}" width="100"><br><br>
            @endif
            <input type="file" name="image" class="form-control">
        </div>

        <button type="submit" class="btn btn-success">Update</button>
    </form>
</div>
@endsection
