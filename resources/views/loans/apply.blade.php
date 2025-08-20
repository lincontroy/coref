@extends('layouts.dashboard')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card shadow">
                <div class="card-header">Choose Loan Category</div>

                <div class="card-body">
                    <div class="row g-4">

                        {{-- Car Loan --}}
                        <div class="col-md-4">
                            <a href="{{ route('loan.cars') }}" class="text-decoration-none">
                                <div class="card text-center h-100 p-4 category-card">
                                    <i class="fas fa-car fa-3x mb-3 text-primary"></i>
                                    <h5>Car Loan</h5>
                                    <p class="small text-muted">Own your dream car with flexible financing</p>
                                </div>
                            </a>
                        </div>

                        {{-- BodaBoda Loan --}}
                        <div class="col-md-4">
                            <a href="{{ route('loan.bodaboda') }}" class="text-decoration-none">
                                <div class="card text-center h-100 p-4 category-card">
                                    <i class="fas fa-motorcycle fa-3x mb-3 text-success"></i>
                                    <h5>BodaBoda Loan</h5>
                                    <p class="small text-muted">Affordable motorcycles with easy repayment</p>
                                </div>
                            </a>
                        </div>

                        {{-- Education Loan --}}
                        <div class="col-md-4">
                            <a href="{{ route('loan.education') }}" class="text-decoration-none">
                                <div class="card text-center h-100 p-4 category-card">
                                    <i class="fas fa-graduation-cap fa-3x mb-3 text-info"></i>
                                    <h5>Education Loan</h5>
                                    <p class="small text-muted">Finance school & university fees with ease</p>
                                </div>
                            </a>
                        </div>

                        {{-- Kilimo Loan --}}
                        <div class="col-md-4">
                            <a href="{{ route('loan.kilimo') }}" class="text-decoration-none">
                                <div class="card text-center h-100 p-4 category-card">
                                    <i class="fas fa-tractor fa-3x mb-3 text-warning"></i>
                                    <h5>Kilimo Loan</h5>
                                    <p class="small text-muted">Support for farmers & agribusiness projects</p>
                                </div>
                            </a>
                        </div>

                        {{-- Emergency Loan --}}
                        <div class="col-md-4">
                            <a href="{{ route('loan.emergency') }}" class="text-decoration-none">
                                <div class="card text-center h-100 p-4 category-card">
                                    <i class="fas fa-ambulance fa-3x mb-3 text-danger"></i>
                                    <h5>Emergency Loan</h5>
                                    <p class="small text-muted">Quick funds when you need them most</p>
                                </div>
                            </a>
                        </div>

                        {{-- Business Loan --}}
                        <div class="col-md-4">
                            <a href="{{ route('loan.business') }}" class="text-decoration-none">
                                <div class="card text-center h-100 p-4 category-card">
                                    <i class="fas fa-briefcase fa-3x mb-3 text-dark"></i>
                                    <h5>Business Loan</h5>
                                    <p class="small text-muted">Expand and grow your business operations</p>
                                </div>
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .category-card {
        cursor: pointer;
        transition: 0.3s;
        border: 1px solid #eee;
        border-radius: 12px;
    }
    .category-card:hover {
        transform: translateY(-5px);
        background: #f8f9fa;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
</style>
@endsection
