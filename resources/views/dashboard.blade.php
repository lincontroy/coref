@extends('layouts.dashboard')
@section('title', 'Dashboard')

@section('content')

@php
$user = Auth::user();
$userDetails = $user->userDetails;
$profileComplete = $userDetails && $userDetails->employment_details_completed && $userDetails->financial_details_completed;
$completionPercentage = $userDetails ? $userDetails->profile_completion_percentage : 0;

// Loan statistics
$totalLoans = $user->loans()->count();
$approvedLoans = $user->loans()->where('status', 'approved')->count();
$outstandingBalance = $user->loans()->where('status', 'disbursed')->sum('approved_amount');
$totalBorrowed = $user->loans()->whereIn('status', ['disbursed', 'repaid'])->sum('approved_amount');
@endphp

<div class="row">
    <div class="col-lg-3 col-6">
        <!-- small card -->
        <div class="small-box bg-info">
            <div class="inner">
                <h5>{{ $totalLoans }}</h5>
                <p>Total Loans</p>
            </div>
            <div class="icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-6">
        <!-- small card -->
        <div class="small-box bg-success">
            <div class="inner">
                <h5>KES {{ number_format($outstandingBalance, 2) }}</h5>
                <p>Outstanding Loan Balance</p>
            </div>
            <div class="icon">
                <i class="ion ion-stats-bars"></i>
            </div>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-6">
        <!-- small card -->
        <div class="small-box bg-warning">
            <div class="inner">
                <h5>KES {{ number_format($totalBorrowed, 2) }}</h5>
                <p>Accumulated loans</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-plus"></i>
            </div>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-6">
        <!-- small card -->
        <div class="small-box bg-danger">
            <div class="inner">
                <h5>{{ $approvedLoans }}</h5>
                <p>Approved Loans</p>
            </div>
            <div class="icon">
                <i class="fas fa-chart-pie"></i>
            </div>
        </div>
    </div>
    <!-- ./col -->
</div>

<!-- Profile Completion Alert (only show if profile is incomplete) -->
@if(!$profileComplete)
<div class="row">
    <div class="col-lg-12">
        <div class="alert alert-warning alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h4><i class="icon fa fa-warning"></i> Profile Incomplete!</h4>
            <p>You need to complete your profile before applying for loans. Your profile is {{ $completionPercentage }}% complete.</p>
            <div class="progress progress-sm">
                <div class="progress-bar bg-warning" style="width: {{ $completionPercentage }}%"></div>
            </div>
            <div class="mt-2">
                <a href="{{ route('user-details.index') }}" class="btn btn-warning btn-sm">
                    <i class="fas fa-user-edit"></i> Complete Profile
                </a>
            </div>
        </div>
    </div>
</div>
@endif

<div class="row">
    <div class="col-lg-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h5 class="card-title m-0">Loans</h5>
                <div class="card-tools">
                    @if($profileComplete)
                        <a href="{{ route('loan.apply') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Apply Loan
                        </a>
                    @else
                        <button type="button" class="btn btn-secondary btn-sm" disabled 
                                data-toggle="tooltip" data-placement="left" 
                                title="Complete your profile to apply for loans">
                            <i class="fas fa-lock"></i> Complete Profile to Apply
                        </button>
                        <a href="{{ route('user-details.index') }}" class="btn btn-warning btn-sm ml-2">
                            <i class="fas fa-user-edit"></i> Complete Profile
                        </a>
                    @endif
                </div>
            </div>
            <div class="card-body">
                @if(!$profileComplete)
                    <div class="callout callout-info">
                        <h5><i class="fas fa-info"></i> Notice:</h5>
                        Complete your employment and financial details to unlock loan applications.
                        <a href="{{ route('user-details.index') }}" class="btn btn-info btn-xs ml-2">
                            Go to Profile
                        </a>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Loan ID</th>
                                <th>Purpose</th>
                                <th>Requested Amount</th>
                                <th>Approved Amount</th>
                                <th>Status</th>
                                <th>Applied On</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($user->loans()->latest()->get() as $loan)
                            <tr>
                                <td>#{{ $loan->id }}</td>
                                <td>{{ $loan->purpose }}</td>
                                <td>KES {{ number_format($loan->requested_amount, 2) }}</td>
                                <td>
                                    @if($loan->approved_amount)
                                        KES {{ number_format($loan->approved_amount, 2) }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @switch($loan->status)
                                        @case('pending')
                                            <span class="badge badge-warning">Pending Review</span>
                                            @break
                                        @case('approved')
                                            <span class="badge badge-info">Approved</span>
                                            @break
                                        @case('rejected')
                                            <span class="badge badge-danger">Rejected</span>
                                            @break
                                        @case('processing_disbursement')
                                            <span class="badge badge-primary">Processing</span>
                                            @break
                                        @case('disbursed')
                                            <span class="badge badge-success">Disbursed</span>
                                            @break
                                        @case('repaid')
                                            <span class="badge badge-secondary">Repaid</span>
                                            @break
                                        @default
                                            <span class="badge badge-secondary">Unknown</span>
                                    @endswitch
                                </td>
                                <td>{{ $loan->created_at->format('M d, Y') }}</td>
                                <td>
                                    <a href="{{ route('loan.status', $loan) }}" class="btn btn-xs btn-info">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">
                                    @if(!$profileComplete)
                                        <p class="text-muted">Complete your profile to start applying for loans.</p>
                                        <a href="{{ route('user-details.index') }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-user-edit"></i> Complete Profile Now
                                        </a>
                                    @else
                                        <p class="text-muted">No loans found. Apply for your first loan!</p>
                                        <a href="{{ route('loan.apply') }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-plus"></i> Apply Now
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="d-flex justify-content-center">
                <!-- Pagination can go here if needed -->
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(function () {
        // Initialize tooltips
        $('[data-toggle="tooltip"]').tooltip();
        
        // Show profile incomplete modal when trying to access loan application
        $('.btn-secondary[disabled]').click(function(e) {
            e.preventDefault();
            $('#profileIncompleteModal').modal('show');
        });
    });
</script>
@endsection