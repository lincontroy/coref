@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Loan Applications</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">All Loan Applications</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Requested Amount</th>
                            <th>Approved Amount</th>
                            <th>Status</th>
                            <th>Applied On</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($loans as $loan)
                        <tr>
                            <td>#{{ $loan->id }}</td>
                            <td>{{ $loan->user->name }}</td>
                            <td>KES {{ number_format($loan->requested_amount, 2) }}</td>
                            <td>
                                @if($loan->approved_amount)
                                    KES {{ number_format($loan->approved_amount, 2) }}
                                @else
                                    -
                                @endif
                            </td>
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
                                <a href="{{ route('admin.loans.show', $loan) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{ $loans->links() }}
            </div>
        </div>
    </div>
</div>
@endsection