@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Admin Dashboard</h1>

    <div class="row">
        <!-- Metrics Cards -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Loans</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $metrics['total_loans'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Repeat similar cards for other metrics -->
        @foreach([
            'pending_loans' => ['warning', 'Pending Loans', 'hourglass-half'],
            'approved_loans' => ['info', 'Approved Loans', 'check-circle'],
            'disbursed_loans' => ['success', 'Disbursed Loans', 'money-bill-wave'],
            'total_amount' => ['primary', 'Total Amount (KES)', 'dollar-sign'],
            'new_users' => ['secondary', 'New Users Today', 'users']
        ] as $key => $config)
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-{{ $config[0] }} shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-{{ $config[0] }} text-uppercase mb-1">
                                {{ $config[1] }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                @if($key === 'total_amount')
                                    {{ number_format($metrics[$key], 2) }}
                                @else
                                    {{ $metrics[$key] }}
                                @endif
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-{{ $config[2] }} fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Recent Loans Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Recent Loan Applications</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Applied On</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentLoans as $loan)
                        <tr>
                            <td>#{{ $loan->id }}</td>
                            <td>{{ $loan->user->name }}</td>
                            <td>KES {{ number_format($loan->requested_amount, 2) }}</td>
                            <td>
                                <span class="badge badge-{{ 
                                    $loan->status == 'approved' ? 'success' : 
                                    ($loan->status == 'rejected' ? 'danger' : 'warning')
                                }}">
                                    {{ ucfirst($loan->status) }}
                                </span>
                            </td>
                            <td>{{ $loan->created_at->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ route('admin.loans.show', $loan) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection