@extends('layouts.dashboard')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Pay Application Fee</div>

                <div class="card-body">
                    <div class="alert alert-info">
                        <p>Please pay the application fee of <strong>KES {{ number_format($loan->application_fee, 2) }}</strong> to complete your loan application.</p>
                    </div>

                    <div class="text-center mb-4">
                        <button id="initiateStk" class="btn btn-primary btn-lg">
                            <i class="bi bi-phone"></i> Pay via M-Pesa
                        </button>
                    </div>

                    <div class="border p-3 mb-4">
                        <h5>Manual Payment Instructions</h5>
                        <ol>
                            <li>Go to M-Pesa on your phone</li>
                            <li>Select Lipa na M-Pesa</li>
                            <li>Enter Till Number: <strong>4161912</strong></li>
                            <li>Enter Amount: <strong>KES {{ number_format($loan->application_fee, 2) }}</strong></li>
                            <li>Enter your PIN and confirm</li>
                        </ol>
                    </div>

                    <div class="text-center">
                        <a href="{{ route('loan.confirm_payment', $loan) }}" class="btn btn-success">
                            I've Already Paid
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('initiateStk').addEventListener('click', function() {
    // In a real app, this would call your backend to initiate STK push
    alert('STK push would be initiated here in production');
    
    // Simulate payment success after 3 seconds
    setTimeout(function() {
        window.location.href = "{{ route('loan.confirm_payment', $loan) }}";
    }, 3000);
});
</script>
@endpush
@endsection