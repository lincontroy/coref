@extends('layouts.main')

@section('content')
<section class="sign-up section">
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
                    <form method="POST" action="{{url('/register/post')}}" autocomplete="off" class="sign-up__form me-lg-4 me-xxl-0 wow fadeInUp">          
                    @csrf     
                   
                    
                    <h3 class="sign-up__title wow fadeInUp" data-wow-duration="0.8s">Let’s Get Started!</h3>
                   
          
                @yield('content')
                        <div class="sign-up__form-part">
                            
                                <div class="input-single">
                                    <label class="label" for="fname">Full Name</label>
                                    <input type="text" class="form-control" name="fname" id="fname" placeholder="Enter Full Name..." required>
                                </div>
                               
                           
                            <div class="input-single">
                                <label class="label" for="email">Enter Your Email </label>
                                <input type="email" class="form-control" name="email" id="email" placeholder="Enter Your Email..." required>
                            </div>

                            <div class="input-single">
                                <label class="label" for="email">Enter Your phone </label>
                                <input type="tel" class="form-control" name="tel" id="phone" placeholder="Enter Your phone number..." required>
                            </div>
                            <div class="input-single">
                                <label class="label" for="email">Enter Your National Id </label>
                                <input type="number" class="form-control" name="nationalId" id="national" placeholder="Enter Your National id..." required>
                            </div>
                            <!-- <div class="input-single">
                                <label class="label" for="email">Select Loan type </label>
                                <select class="form-control" name="loantype">
                                    <option value="">====Select Loan type====</option>
                                    <option value="Emergency mkopo">Emergency mkopo</option>
                                    <option value="Car mkopo">Car mkopo</option>
                                    <option value="Education mkopo">Education mkopo</option>       
                                    <option value="Rental mkopo">Rental mkopo</option>
                                    <option value="Business mkopo">Business mkopo</option>
                                </select>
                            </div> -->
                            <div class="input-single">
                                <label class="label" for="password">Enter Your Password</label>
                                <div class="input-pass">
                                    <input type="password" class="form-control" name="password" id="password" placeholder="Enter Your Password..." required>
                                    <span class="password-eye-icon"></span>
                                </div>
                            </div>
                        </div>
                        <p class="have_account mt_24">Do you have an account? <a href="/login" class="signin">Sign in</a></p>
                        <span id="msg"></span> 
                        <button type="submit" class="btn_theme mt_32" name="submit">Register<i class="bi bi-arrow-up-right"></i><span></span></button> 
                    </form>
                </div>
                <div class="col-12 col-sm-7 col-lg-5 col-xxl-5">
                    <div class="sign-up__thumb previewShapeY unset-xxl wow fadeInDown" data-wow-duration="0.8s">
                        <img src="assets/images/sign_up.png" alt="images">
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection