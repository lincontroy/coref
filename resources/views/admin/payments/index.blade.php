<?php 
    $payments = App\Models\PayheroResponse::orderBy('id','DESC')->get();
?>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">All Payments</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="paymentsTable" width="100%" cellspacing="0">
                <thead class="thead-light">
                    <tr>
                        <th>ID</th>
                        <th>Transaction ID</th>
                        <th>Phone</th>
                        <th>Amount</th>
                        <th>Status</th>
                        
                        <th>Paid At</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                    <tr>
                        <td>#{{ $payment->id }}</td>
                        <td>{{ $payment->mpesa_receipt_number ?? 'N/A' }}</td>
                        <td>{{ $payment->phone ?? 'N/A' }}</td>
                        <td>KES {{ number_format($payment->amount, 2) }}</td>
                        <td>
                            <span class="badge badge-{{ $payment->status == 'Success' ? 'success' : 'danger' }}">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </td>
                       
                        <td>{{ $payment->created_at->format('M d, Y h:i A') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No payments found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('#paymentsTable').DataTable({
        responsive: true,
        dom: '<"top"f>rt<"bottom"lip><"clear">',
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search payments...",
        }
    });
});
</script>
@endpush
