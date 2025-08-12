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

    <?php $users=App\Models\User::orderBy('id','DESC')->get();?>

    <div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">All Users</h6>
       
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="usersTable" width="100%" cellspacing="0">
                <thead class="thead-light">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Wallet Balance</th>
                        <th>Status</th>
                        <th>Registered On</th>
                        <th>Last Login</th>
                       
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>#{{ $user->id }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="mr-2">
                                    <img class="rounded-circle" width="40" src="https://upload.wikimedia.org/wikipedia/commons/9/99/Sample_User_Icon.png?20200919003010" alt="{{ $user->name }}">
                                </div>
                                <div>
                                    <div class="font-weight-bold">{{ $user->name }}</div>
                                    <div class="small text-muted">ID: {{ $user->unique_id ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone ?? 'N/A' }}</td>
                        <td class="font-weight-bold {{ $user->wallet < 0 ? 'text-danger' : 'text-success' }}">
                            KES {{ number_format($user->wallet, 2) }}
                        </td>
                        <td>
                            <span class="badge badge-{{ $user->is_active ? 'success' : 'danger' }}">
                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>{{ $user->created_at->format('M d, Y') }}</td>
                        <td>
                            @if($user->last_login_at)
                                {{ $user->last_login_at->diffForHumans() }}
                            @else
                                Never
                            @endif
                        </td>
                        
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">No users found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
       
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#usersTable').DataTable({
        responsive: true,
        dom: '<"top"f>rt<"bottom"lip><"clear">',
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search users...",
        }
    });

    // Delete user confirmation
    $('.delete-user').click(function() {
        const userId = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                axios.delete(`/admin/users/${userId}`)
                    .then(response => {
                        Swal.fire(
                            'Deleted!',
                            'User has been deleted.',
                            'success'
                        ).then(() => window.location.reload());
                    })
                    .catch(error => {
                        Swal.fire(
                            'Error!',
                            'There was an error deleting the user.',
                            'error'
                        );
                    });
            }
        });
    });
});
</script>
@endpush
</div>
@endsection