@extends('layouts.dashboard')

@section('content')
<section class="py-5">
    <div class="container">
        <h2 class="fw-bold mb-4">Apply for {{ $boda->name }}</h2>
        <div class="card shadow p-4">
            <p><strong>Boda Boda Price:</strong> KES {{ number_format($boda->price) }}</p>
            <p><strong>Required Deposit (15%):</strong> <span class="text-success fw-bold">KES {{ number_format($deposit) }}</span></p>

            <form method="POST" action="{{ route('boda.loan.processPayment', $boda->id) }}">
                @csrf
                <div class="mb-3">
                    <label for="phone" class="form-label">Enter M-Pesa Phone Number</label>
                    <input type="text" name="phone" id="phone" class="form-control" placeholder="2547XXXXXXXX" required>
                </div>
                <button type="submit" class="btn btn-success btn-lg">
                    Pay Deposit & Apply <i class="bi bi-cash-stack ms-2"></i>
                </button>
            </form>

            @if(session('success'))
                <div class="alert alert-success mt-3">{!! session('success') !!}</div>
            @endif
        </div>
    </div>
</section>
@endsection
