@extends('layouts.dashboard')
@section('title', 'Apply Loan')

@section('content')
<div class="row">
    <div class="col-lg-12">


        <div class="container mt-5">
            <h2 class="mb-4">Loan Application Form</h2>
            <form action="{{route('apply.post')}}" method="post">
                @csrf
                <div class="form-group">
                    <label for="loanType">Loan Type</label>
                    <select class="form-control" id="loanType" name="loanType">
                        <option value="">====Select Loan type====</option>
                        <option value="Emergency mkopo">Emergency mkopo KES 2,500 (max)</option>
                        <option value="Car mkopo">Car mkopo KES 500,000 (max)</option>
                        <option value="Education mkopo">Education mkopo KES 25,000(max)</option>
                        <option value="Rental mkopo">Rental mkopo KES 20,000(max)</option>
                        <option value="Business mkopo">Business mkopo KES 50,000(max)</option>
                        <option value="Personal loan">Personal loan KES 10,000(max)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="amount">Loan Amount</label>
                    <input type="text" class="form-control" id="amount" name="amount" placeholder="Enter Loan Amount" required>
                </div>
                <div class="form-group">
                    <label for="duration">Loan Duration (months)</label>
                    <input type="text" class="form-control" id="duration" name="duration"
                        placeholder="Loan Duration in months" required>
                </div>
                <div class="form-group">
                    <label for="purpose">Purpose of Loan</label>
                    <textarea class="form-control" id="purpose" name="purpose" rows="3"
                        placeholder="Enter purpose of loan" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>

    </div>
</div>

@endsection
