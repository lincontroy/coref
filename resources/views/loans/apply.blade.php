@extends('layouts.dashboard')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Loan Application</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('loan.apply') }}">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="purpose">Purpose of Loan</label>
                            <select class="form-control" id="purpose" name="purpose" required>
                                <option value="">Select purpose</option>
                                <option value="Business">Business</option>
                                <option value="Education">Education</option>
                                <option value="Emergency">Emergency</option>
                                <option value="Home Improvement">Home Improvement</option>
                                <option value="Personal">Personal</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="amount">Loan Amount (KES)</label>
                            <input type="number" class="form-control" id="amount" name="amount" 
                                   min="1000" max="500000" step="100" required>
                            <small class="text-muted">Minimum: 1,000 KES | Maximum: 500,000 KES</small>
                        </div>

                        <div class="form-group mb-3">
                            <label>Repayment Period</label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="repayment_period" id="30days" value="30" required>
                                <label class="btn btn-outline-primary" for="30days">30 Days</label>

                                <input type="radio" class="btn-check" name="repayment_period" id="90days" value="90">
                                <label class="btn btn-outline-primary" for="90days">90 Days</label>

                                <input type="radio" class="btn-check" name="repayment_period" id="6months" value="180">
                                <label class="btn btn-outline-primary" for="6months">6 Months</label>

                                <input type="radio" class="btn-check" name="repayment_period" id="12months" value="360">
                                <label class="btn btn-outline-primary" for="12months">12 Months</label>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <button type="submit" class="btn btn-primary w-100">
                                Apply for Loan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection