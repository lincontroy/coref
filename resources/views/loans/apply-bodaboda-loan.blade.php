@extends('layouts.dashboard')

@section('content')
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-success text-white">
            <h4>Apply for Boda Boda Loan - {{ $boda->name }}</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('loan.bodaboda.store') }}" method="POST">
                @csrf
                <input type="hidden" name="boda_id" value="{{ $boda->id }}">

                <div class="mb-3">
                    <label class="form-label">Boda Boda Name</label>
                    <input type="text" class="form-control" value="{{ $boda->name }}" disabled>
                </div>

                <div class="mb-3">
                    <label class="form-label">Price (KES)</label>
                    <input type="text" class="form-control" value="{{ number_format($boda->price) }}" disabled>
                </div>

                <div class="mb-3">
                    <label class="form-label">Loan Amount (KES)</label>
                    <input type="number" name="loan_amount" class="form-control" value="{{ $boda->price }}" required>
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

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('success'))
<script>
    const message = {!! json_encode(session('success')) !!};
    Swal.fire({
        title: '🏍️ Smooth Ride Ahead!',
        html: message,
        icon: 'success',
        confirmButtonText: 'Vroom! Proceed to Pay'
    });
</script>
@endif
@endsection
