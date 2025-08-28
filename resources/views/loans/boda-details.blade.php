@extends('layouts.dashboard')

@section('content')
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-success text-white">
            <h4>{{ $boda->name }}</h4>
        </div>
        <div class="card-body">
            <img src="{{ asset($boda->image) }}" class="img-fluid mb-3" alt="{{ $boda->name }}">
            <p><strong>Price:</strong> KES {{ number_format($boda->price) }}</p>
            <p><strong>Description:</strong> {{ $boda->description }}</p>

            <a href="{{ route('boda.loan.apply.loan', ['id' => $boda['id']]) }}" class="btn btn-success btn-lg mt-3">
                Buy via Hire Purchase<i class="bi bi-arrow-right-circle ms-2"></i>
             </a>


            <a href="{{ route('loan.bodaboda.apply', $boda->id) }}" class="btn btn-success btn-lg mt-3">
                Apply for Loan
            </a>

            <a href="{{ route('loan.bodaboda') }}" class="btn btn-outline-secondary btn-lg mt-3 ms-2">
                Back to List
            </a>
        </div>
    </div>
</div>
@endsection
