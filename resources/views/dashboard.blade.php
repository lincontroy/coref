@extends('layouts.dashboard')
@section('title', 'Dashboard')

@section('content')

<div class="row">
    <div class="col-lg-3 col-6">
        <!-- small card -->
        <div class="small-box bg-info">
            <div class="inner">
                <h3>
                {{$loans=\App\Models\Loans::where('user',Auth::user()->id)->count()}}
                </h3>

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
                <h3>KES 0</h3>

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
                <h3>
                    0

                </h3>

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
                <h3>0</h3>

                <p>Approved Loans</p>
            </div>
            <div class="icon">
                <i class="fas fa-chart-pie"></i>
            </div>
        </div>
    </div>
    <!-- ./col -->
</div>
<div class="row">


    <div class="col-lg-12">

        <div class="card card-primary card-outline">
            <div class="card-header">
                <h5 class="card-title m-0">Loans</h5>
                <div class="card-tools">
                    <a href="apply/loan" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                        data-bs-target="#exampleModal"><i class="fas fa-plus"></i>
                        Apply Loan
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Loan Id</th>
                                <th>Loan Type</th>
                                <th>Amount qualified</th>
                                <th>Status</th>
                                <th>Created At</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $loans=\App\Models\Loans::where('user',Auth::user()->id)->orderBy('id','DESC')->get();
                            ?>

                            @foreach($loans as $loan)


                            <tr>
                                <td>{{$loan->id}}</td>
                                <td>{{$loan->loanType}}</td>
                                <td>{{$loan->amount}}</td>
                                @if($loan->status==null)
                                <td>Unprocessed</td>
                                @else
                                <td>Processed</td>
                                @endif

                            
                                
                                <td>{{$loan->created_at}}</td>
                            </tr>

                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="d-flex justify-content-center">

            </div>
        </div>
    </div>
</div>
<!-- /.col-md-6 -->
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
<!-- /.row -->
@endsection
@section('scripts')

<script>
    $(function () {

    });

    function confirmDelete(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to delete this member!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#aaa',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#deleteRecord' + id).submit();
            }
        })
    }

</script>
@endsection
