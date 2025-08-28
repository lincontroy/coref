@extends('layouts.dashboard')

@section('content')
<section class="py-5">
    <div class="container">
        <h1 class="fw-bold mb-4 text-center">Available Boda Bodas for Financing</h1>
        <div class="row g-4">
            @foreach($bodas as $boda)
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <img src="{{ asset($boda->image) }}" class="card-img-top" alt="{{ $boda->name }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $boda->name }}</h5>
                            <p class="card-text text-muted">KES {{ number_format($boda->price) }}</p>
                            @if(!empty($boda->description))
                                <p class="card-text">
                                    {{ \Illuminate\Support\Str::limit($boda->description, 100, '...') }}
                                </p>
                            @endif
                            <a href="{{ route('loan.bodaDetails', $boda->id) }}" class="btn btn-success w-100">
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
