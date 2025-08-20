@extends('layouts.dashboard')

@section('content')
<section class="py-5">
    <div class="container">
        <h2 class="fw-bold mb-4">Apply for {{ $vehicle['name'] }}</h2>
        <div class="card shadow p-4">
            <p><strong>Vehicle Price:</strong> KES {{ number_format($vehicle['price']) }}</p>
            <p><strong>Required Deposit (15%):</strong> <span class="text-success fw-bold">KES {{ number_format($deposit) }}</span></p>
            
            <form method="POST" action="{{ route('loan.processPayment', $vehicle['id']) }}">
                @csrf
                <div class="mb-3">
                    <label for="phone" class="form-label">Enter M-Pesa Phone Number</label>
                    <input type="text" name="phone" id="phone" class="form-control" placeholder="2547XXXXXXXX" required>
                </div>
                <button type="submit" class="btn btn-success btn-lg">
                    Pay Deposit & Apply <i class="bi bi-cash-stack ms-2"></i>
                </button>
            </form>
        </div>
    </div>
</section>
@endsection
