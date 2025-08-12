@extends('layouts.dashboard')

@section('title', 'Loan Status')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Loan #{{ $loan->id }} Status</h3>
                    <div class="card-tools">
                        <span class="badge badge-{{ 
                            $loan->status == 'approved' ? 'success' : 
                            ($loan->status == 'rejected' ? 'danger' : 
                            ($loan->status == 'disbursed' ? 'primary' : 'warning'))
                        }}">
                            {{ ucfirst(str_replace('_', ' ', $loan->status)) }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Loan Details</h5>
                            <table class="table table-sm">
                                <tr>
                                    <th>Purpose</th>
                                    <td>{{ $loan->purpose }}</td>
                                </tr>
                                <tr>
                                    <th>Requested Amount</th>
                                    <td>KES {{ number_format($loan->requested_amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Approved Amount</th>
                                    <td>KES {{ number_format($loan->approved_amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Application Fee</th>
                                    <td>
                                        KES {{ number_format($loan->application_fee, 2) }}
                                        <span class="badge badge-{{ $loan->fee_paid ? 'success' : 'danger' }}">
                                            {{ $loan->fee_paid ? 'Paid' : 'Unpaid' }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Timeline</h5>
                            <table class="table table-sm">
                            <tr>
                                <th>Applied On</th>
                                <td>{{ $loan->created_at->timezone(auth()->user()->timezone ?? config('app.timezone'))->format('M d, Y h:i A') }}</td>
                            </tr>
                            @if($loan->disbursed_at)
                            <tr>
                                <th>Disbursed On</th>
                                <td>
    {{ $loan->disbursed_at ? Carbon\Carbon::parse($loan->disbursed_at)->timezone(auth()->user()->timezone ?? config('app.timezone'))->format('M d, Y h:i A') : 'N/A' }}
</td> </tr>
                            @endif
                             
                                <tr>
                                    <th>Repayment Period</th>
                                    <td>{{ $loan->repayment_period }} days</td>
                                </tr>
                                @if($loan->due_date)
                                <tr>
                                    <th>Due Date</th>
                                    <td>{{ \Carbon\Carbon::parse($loan->due_date)->format('M d, Y') }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    @if($repaymentInfo)
                    <div class="alert alert-info">
                        <h5><i class="fas fa-info-circle"></i> Repayment Information</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Total Repayment:</strong> KES {{ number_format($repaymentInfo['total_repayment'], 2) }}</p>
                                <p><strong>Interest Rate:</strong> {{ $repaymentInfo['interest_rate'] }}%</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Daily Repayment:</strong> KES {{ number_format($repaymentInfo['daily_repayment'], 2) }}</p>
                                <p><strong>Days Remaining:</strong> {{ $repaymentInfo['days_remaining'] }} days</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="mt-4">
                        @if($loan->status == 'approved' && !$loan->fee_paid)
                            <a href="{{ route('loan.pay_fee', $loan) }}" class="btn btn-primary">
                                <i class="fas fa-money-bill-wave"></i> Pay Application Fee
                            </a>
                        @endif

                       

                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if($loan->status == 'disbursed')
<!-- Make Payment Modal -->
<div class="modal fade" id="makePaymentModal" tabindex="-1" role="dialog" aria-labelledby="makePaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="makePaymentModalLabel">Make Loan Payment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="paymentForm" action="{{ route('loan.payment', $loan) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="paymentAmount">Amount (KES)</label>
                        <input type="number" class="form-control" id="paymentAmount" name="amount" 
                               min="100" max="{{ $repaymentInfo['total_repayment'] }}" 
                               step="100" required>
                    </div>
                    <div class="form-group">
                        <label for="paymentMethod">Payment Method</label>
                        <select class="form-control" id="paymentMethod" name="method" required>
                            <option value="mpesa">M-Pesa</option>
                            <option value="bank">Bank Transfer</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit Payment</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Initialize payment modal if shown
    $('#makePaymentModal').on('shown.bs.modal', function () {
        $('#paymentAmount').focus();
    });

    // Payment form validation
    $('#paymentForm').validate({
        rules: {
            amount: {
                required: true,
                min: 100,
                max: {{ $repaymentInfo['total_repayment'] ?? 0 }}
            },
            method: {
                required: true
            }
        },
        messages: {
            amount: {
                min: "Minimum payment is KES 100",
                max: "Payment cannot exceed total repayment amount"
            }
        }
    });
});
</script>
@endsection