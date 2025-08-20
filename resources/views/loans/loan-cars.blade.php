@extends('layouts.dashboard')

@section('content')
<div class="container py-5">
    <h3 class="mb-4">Available Cars for Financing</h3>
    <div class="row g-4">

        {{-- Toyota Axio --}}
        <div class="col-md-6">
            <div class="card h-100 shadow-sm car-card">
                <img src="/images/cars/axio.jpg" class="card-img-top" alt="Toyota Axio">
                <div class="card-body">
                    <h5 class="card-title">Toyota Axio 2015</h5>
                    <ul class="small">
                        <li>Engine: 1500cc</li>
                        <li>Fuel: Petrol</li>
                        <li>Transmission: Automatic</li>
                        <li>Color: Silver</li>
                        <li>Mileage: 82,000 km</li>
                    </ul>
                    <p><strong>Deposit:</strong> KES 300,000</p>
                    <p><strong>Installments:</strong> From KES 25,000/month</p>
                    <p><strong>Repayment:</strong> 12 – 36 Months</p>
                    <a href="{{ route('loan.apply.form',['type'=>'car','model'=>'Toyota Axio 2015']) }}" class="btn btn-primary w-100">Apply Now</a>
                </div>
            </div>
        </div>

        {{-- Mazda Demio --}}
        <div class="col-md-6">
            <div class="card h-100 shadow-sm car-card">
                <img src="/images/cars/demio.jpg" class="card-img-top" alt="Mazda Demio">
                <div class="card-body">
                    <h5 class="card-title">Mazda Demio 2016</h5>
                    <ul class="small">
                        <li>Engine: 1300cc</li>
                        <li>Fuel: Petrol</li>
                        <li>Transmission: Automatic</li>
                        <li>Color: Blue</li>
                        <li>Mileage: 65,000 km</li>
                    </ul>
                    <p><strong>Deposit:</strong> KES 250,000</p>
                    <p><strong>Installments:</strong> From KES 22,000/month</p>
                    <p><strong>Repayment:</strong> 12 – 36 Months</p>
                    <a href="{{ route('loan.apply.form',['type'=>'car','model'=>'Mazda Demio 2016']) }}" class="btn btn-primary w-100">Apply Now</a>
                </div>
            </div>
        </div>

    </div>

    <div class="alert alert-info mt-4">
        <strong>Note:</strong> A processing fee of <strong>KES 7,000</strong> applies. Agreement signing & vehicle handover will be done <strong>3 working days after processing</strong>.
    </div>
</div>

<style>
    .car-card img {
        height: 200px;
        object-fit: cover;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
    }
</style>
@endsection
