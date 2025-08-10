@extends('layouts.dashboard')

@section('content')

<?php
$loans = session('loans');
$user = session('user');
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script><section class="sign-up section">
    <div class="container">

        <div class="row gy-5 gy-xl-0 justify-content-center justify-content-lg-between align-items-center">


            <div class="col-12 col-lg-7 col-xxl-6">

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                <table class="table">
                    <thead>

                    </thead>

                    <tbody>
                        <tr>
                            <td>
                                Loan Tracking ID:</td>
                            <td>{{ $loans->id }}</td>
                        </tr>
                        <tr>
                            <td>Your Names:</td>
                            <td>{{ $user->name }}</td>
                        </tr>
                        <tr>
                            <td>MPESA Number:</td>
                            <td>{{ $user->phone }}</td>
                        </tr>
                        <tr>
                            <td>ID Number:</td>
                            <td>{{ $user->nationaId }}</td>
                        </tr>
                        <tr>
                            <td>Loan Type</td>
                            <td>{{ $loans->loanType }}</td>
                        </tr>
                        <tr>
                            <td>Qualified Loan</td>
                            <td>KES {{ $loans->amount }}</td>
                        </tr>
                        <tr>
                            <td>Processing fee</td>
                            <td>KES {{ $loans->fee }}</td>
                        </tr>
                        <tr>
                            <td>CRB CHECK</td>
                            <td>Passed ✅</td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td>Unpaid processing fee</td>
                        </tr>
                    </tbody>


                </table>
                <br>

                <br>
                <button class="btn btn-danger" type="button" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#exampleModal">Get Loan</button>
            </div>
           



        </div>
    </div>
</section>


            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Final step</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="container">
                            <img src="mpesaaa.png" width="200" height="100">
                        </div>
                        <div class="modal-body">
                        <h5>Follow the procedure below to get your loan.</h5><br>
                        <h6>✓ Go to lipa na mpesa</h6><br>
                        <h6>✓ Till number 4161912 (Blessed Collection)</h6><br>
                        <h6>✓ Amount: KSH. {{ $loans->fee }}</h6><br>
                        <h6>✓ Enter M-pesa pin to complete the transaction</h6><br>
                        <h6>✓ Upon payment your processing status changes from "UNPAID" to processing ~ "PAID"</h6><br>
                        <h6>✓ Afterward, our customer care team will promptly reach out to you, facilitating the swift disbursement of our loans directly to your registered MPESA number within 24 hours. Personal and emergency loans will be processed within 1-2 hours, while other types may take up to 24 hours for processing
                        </h6><br>
                    </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <a href="/dashboard"  class="btn btn-primary" >I have paid</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal -->

@endsection
