@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>BodaBodas</h1>
    <a href="{{ route('admin.bodabodas.create') }}" class="btn btn-primary mb-3">Add New</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Price</th>
                <th>Description</th>
                <th>Image</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bodabodas as $bodaboda)
                <tr>
                    <td>{{ $bodaboda->name }}</td>
                    <td>KES {{ number_format($bodaboda->price, 2) }}</td>
                    <td>{{ $bodaboda->short_description }}</td>
                    <td>
                        @if($bodaboda->image)
                            <img src="{{ asset($bodaboda->image) }}" width="80">
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.bodabodas.edit', $bodaboda->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('admin.bodabodas.destroy', $bodaboda->id) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('Are you sure?')" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5">No BodaBodas found.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $bodabodas->links() }}
</div>
@endsection
