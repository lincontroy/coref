@extends('layouts.dashboard')

@section('content')
<section class="py-5">
    <div class="container">
        <h1 class="fw-bold mb-4 text-center">Available Vehicles for Financing</h1>
        <div class="row g-4">
            @foreach($vehicles as $vehicle)
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <img src="{{ asset($vehicle['image']) }}" class="card-img-top" alt="{{ $vehicle['name'] }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $vehicle['name'] }}</h5>
                            <p class="card-text text-muted">{{ $vehicle['price'] }}</p>
                            <a href="{{ route('loan.carDetails', $vehicle['id']) }}" class="btn btn-primary w-100">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
