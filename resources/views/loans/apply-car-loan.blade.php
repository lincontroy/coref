@extends('layouts.dashboard')

@section('content')

@if(session('success'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const fee = Number({{ session('processingFee') }});
    const vehicle = {!! json_encode(session('vehicleName')) !!};
    const message = {!! json_encode(session('success')) !!};

    Swal.fire({
        title: '🏁 Smooth Ride Ahead!',
        html: `
            <p>${message}</p>
            
            <p>Once paid, click the confirmation button below!</p>
        `,
        icon: 'success',
        confirmButtonText: 'I Confirm Payment'
    });
</script>
@endif
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4>Car Loan Application</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('car.loan.apply.loan.store') }}" method="POST">
                @csrf
                <input type="hidden" name="vehicle_id" value="{{ $vehicle->id }}">

                <div class="mb-3">
                    <label class="form-label">Vehicle</label>
                    <input type="text" class="form-control" value="{{ $vehicle->name }}" disabled>
                </div>

                <div class="mb-3">
                    <label class="form-label">Vehicle Price (KES)</label>
                    <input type="number" class="form-control" value="{{ ($vehicle->price) }}" disabled>
                </div>

                <div class="mb-3">
                    <label class="form-label">Loan Amount Requested (KES)</label>
                    <input type="number" name="loan_amount" class="form-control" value="{{ $vehicle->price }}" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label">Purpose of Loan / Notes</label>
                    <textarea name="loan_purpose" class="form-control" placeholder="Optional notes"></textarea>
                </div>

                <button type="submit" class="btn btn-success">Submit Application</button>
            </form>
        </div>
    </div>
</div>
@endsection
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@section('scripts')








@endsection
