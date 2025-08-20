@extends('layouts.dashboard')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row">
            <!-- Image -->
            <div class="col-md-6 mb-4">
                <img src="{{ asset($vehicle['image']) }}" alt="{{ $vehicle['name'] }}" class="img-fluid rounded shadow">
            </div>

            <!-- Details -->
            <div class="col-md-6">
                <h2 class="fw-bold">{{ $vehicle['name'] }}</h2>
                <h4 class="text-primary mb-3">{{ $vehicle['price'] }}</h4>
                <p class="fs-5">{{ $vehicle['description'] }}</p>

                <a href="{{ route('car.loan.apply', ['id' => $vehicle['id']]) }}" class="btn btn-success btn-lg mt-3">
                    Apply for Loan <i class="bi bi-arrow-right-circle ms-2"></i>
                </a>
                
                <a href="{{ route('loan.cars') }}" class="btn btn-outline-secondary btn-lg mt-3 ms-2">
                    Back to List
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
