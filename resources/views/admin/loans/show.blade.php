@extends('layouts.admin')

@section('content')

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Loan Details #{{ $loan->id }}</h1>
    </div>

    @if($loan->status == 'pending')
<form action="{{ route('admin.loans.approve', $loan) }}" method="POST" class="d-inline">
    @csrf
    <div class="form-group">
        <label for="approved_amount">Applied Amount (KES)</label>
        <input type="number" class="form-control" id="approved_amount" name="approved_amount" 
               value="{{ $loan->requested_amount }}" min="1000" max="{{ $loan->requested_amount }}" required>
    </div>
    <button type="submit" class="btn btn-success">
        <i class="fas fa-check"></i> Approve Loan
    </button>
</form>
<form action="{{ route('admin.loans.reject', $loan) }}" method="POST" class="d-inline ml-2">
    @csrf
    <button type="submit" class="btn btn-danger">
        <i class="fas fa-times"></i> Reject Loan
    </button>
</form>
@elseif($loan->status == 'approved')
<form action="{{ route('admin.loans.disburse', $loan) }}" method="POST" class="d-inline">
    @csrf
    <button type="submit" class="btn btn-primary">
        <i class="fas fa-money-bill-wave"></i> Disburse to Wallet
    </button>
</form>
@endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Loan Information</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr>
                            <th>User</th>
                            <td>{{ $loan->user->name }}</td>
                        </tr>
                        <tr>
                            <th>Requested Amount</th>
                            <td>KES {{ number_format($loan->requested_amount, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Approved Amount</th>
                            <td>
                                @if($loan->approved_amount)
                                    KES {{ number_format($loan->approved_amount, 2) }}
                                @else
                                    <span class="text-muted">Not approved yet</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Repayment Period</th>
                            <td>{{ $loan->repayment_period }} days</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr>
                            <th>Status</th>
                            <td>
                                <span class="badge badge-{{ 
                                    $loan->status == 'approved' ? 'success' : 
                                    ($loan->status == 'rejected' ? 'danger' : 'warning')
                                }}">
                                    {{ ucfirst($loan->status) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Applied On</th>
                            <td>{{ $loan->created_at->format('M d, Y h:i A') }}</td>
                        </tr>
                        @if($loan->due_date)
                        <tr>
                            <th>Due Date</th>
                            <td>{{ \Carbon\Carbon::parse($loan->due_date)->format('M d, Y') }}</td>

                        </tr>
                        @endif
                        @if($loan->disbursed_at)
                        <tr>
                            <th>Disbursed On</th>
                            <td>{{ \Carbon\Carbon::parse($loan->disbursed_at)->format('M d, Y h:i A') }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>

         
        </div>
    </div>
</div>
@endsection