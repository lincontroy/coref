@extends('layouts.dashboard')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Your Loan Offer</div>

                <div class="card-body">
                    @if($loan->status == 'approved')
                        <div class="alert alert-success">
                            <h4 class="alert-heading">Congratulations!</h4>
                            <p>Your loan application has been approved.</p>
                        </div>

                        <div class="table-responsive mb-4">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Requested Amount</th>
                                    <td>KES {{ number_format($loan->requested_amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Approved Amount</th>
                                    <td>KES {{ number_format($loan->approved_amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Repayment Period</th>
                                    <td>
    {{ $loan->repayment_period }} days 
    @if($loan->due_date)
        (Due: {{ \Carbon\Carbon::parse($loan->due_date)->format('M d, Y') }})
    @endif
</td></tr>
                                <tr>
                                    <th>Application Fee</th>
                                    <td>KES {{ number_format($loan->application_fee, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Total Repayment</th>
                                    <td>KES {{ number_format($loan->approved_amount * 1.15, 2) }} <small>(15% interest)</small></td>
                                </tr>
                            </table>
                        </div>

                        <div class="d-grid gap-2">
                            <a href="{{ route('loan.pay_fee', $loan) }}" class="btn btn-primary">
                                Pay Application Fee to Continue
                            </a>
                            <a href="#" class="btn btn-outline-secondary">
                                Cancel Application
                            </a>
                        </div>
                    @else
                        <div class="alert alert-danger">
                            <h4 class="alert-heading">Application Not Approved</h4>
                            <p>We're sorry, your loan application wasn't approved at this time.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection