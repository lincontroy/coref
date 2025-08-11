@extends('layouts.dashboard')
@section('title', 'Profile Details')

@section('content')

<!-- Profile completion cards -->
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>
                    {{ $userDetails ? $userDetails->profile_completion_percentage : 0 }}%
                </h3>
                <p>Profile Complete</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-check"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>
                    {!! $userDetails ? $userDetails->employment_status_badge : '<span class="badge badge-secondary">Not Set</span>' !!}
                </h3>
                <p>Employment Status</p>
            </div>
            <div class="icon">
                <i class="fas fa-briefcase"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>
                    {{ $userDetails ? $userDetails->formatted_monthly_income : 'KES 0' }}
                </h3>
                <p>Monthly Income</p>
            </div>
            <div class="icon">
                <i class="fas fa-money-bill-wave"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>
                    {{ $userDetails && $userDetails->bank_name ? 'Connected' : 'Not Set' }}
                </h3>
                <p>Bank Details</p>
            </div>
            <div class="icon">
                <i class="fas fa-university"></i>
            </div>
        </div>
    </div>
</div>

<!-- Employment Details Form -->
<div class="row">
    <div class="col-lg-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h5 class="card-title m-0">Employment & Income Details</h5>
                <div class="card-tools">
                    @if($userDetails && $userDetails->employment_details_completed)
                        <span class="badge badge-success">Completed</span>
                    @else
                        <span class="badge badge-warning">Incomplete</span>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('user-details.employment.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="employment_status">Employment Status <span class="text-danger">*</span></label>
                                <select class="form-control @error('employment_status') is-invalid @enderror" 
                                        id="employment_status" name="employment_status" required>
                                    <option value="">Select employment status</option>
                                    <option value="employed" {{ old('employment_status', $userDetails?->employment_status) == 'employed' ? 'selected' : '' }}>Employed</option>
                                    <option value="self-employed" {{ old('employment_status', $userDetails?->employment_status) == 'self-employed' ? 'selected' : '' }}>Self-Employed</option>
                                    <option value="unemployed" {{ old('employment_status', $userDetails?->employment_status) == 'unemployed' ? 'selected' : '' }}>Unemployed</option>
                                </select>
                                @error('employment_status')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="employer_name">Employer Name</label>
                                <input type="text" class="form-control @error('employer_name') is-invalid @enderror" 
                                       id="employer_name" name="employer_name" 
                                       placeholder="Enter employer name"
                                       value="{{ old('employer_name', $userDetails?->employer_name) }}">
                                @error('employer_name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="job_title">Job Title/Occupation <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('job_title') is-invalid @enderror" 
                                       id="job_title" name="job_title" 
                                       placeholder="Enter job title or occupation"
                                       value="{{ old('job_title', $userDetails?->job_title) }}" required>
                                @error('job_title')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="employment_duration">Duration of Employment</label>
                                <input type="text" class="form-control @error('employment_duration') is-invalid @enderror" 
                                       id="employment_duration" name="employment_duration" 
                                       placeholder="e.g., 2 years 6 months"
                                       value="{{ old('employment_duration', $userDetails?->employment_duration) }}">
                                @error('employment_duration')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="monthly_income">Monthly Income (KES) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('monthly_income') is-invalid @enderror" 
                                       id="monthly_income" name="monthly_income" 
                                       placeholder="Enter monthly income" step="0.01" min="0"
                                       value="{{ old('monthly_income', $userDetails?->monthly_income) }}" required>
                                @error('monthly_income')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="other_income_sources">Other Sources of Income</label>
                                <textarea class="form-control @error('other_income_sources') is-invalid @enderror" 
                                          id="other_income_sources" name="other_income_sources" 
                                          rows="3" placeholder="Describe any other sources of income">{{ old('other_income_sources', $userDetails?->other_income_sources) }}</textarea>
                                @error('other_income_sources')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Employment Details
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Financial Information Form -->
<div class="row">
    <div class="col-lg-12">
        <div class="card card-success card-outline">
            <div class="card-header">
                <h5 class="card-title m-0">Financial Information</h5>
                <div class="card-tools">
                    @if($userDetails && $userDetails->financial_details_completed)
                        <span class="badge badge-success">Completed</span>
                    @else
                        <span class="badge badge-warning">Incomplete</span>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('user-details.financial.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="bank_name">Bank Name (Optional)</label>
                                <select class="form-control @error('bank_name') is-invalid @enderror" 
                                        id="bank_name" name="bank_name">
                                    <option value="">Select your bank</option>
                                    <option value="KCB Bank" {{ old('bank_name', $userDetails?->bank_name) == 'KCB Bank' ? 'selected' : '' }}>KCB Bank</option>
                                    <option value="Equity Bank" {{ old('bank_name', $userDetails?->bank_name) == 'Equity Bank' ? 'selected' : '' }}>Equity Bank</option>
                                    <option value="Cooperative Bank" {{ old('bank_name', $userDetails?->bank_name) == 'Cooperative Bank' ? 'selected' : '' }}>Cooperative Bank</option>
                                    <option value="Standard Chartered" {{ old('bank_name', $userDetails?->bank_name) == 'Standard Chartered' ? 'selected' : '' }}>Standard Chartered</option>
                                    <option value="Barclays Bank" {{ old('bank_name', $userDetails?->bank_name) == 'Barclays Bank' ? 'selected' : '' }}>Barclays Bank</option>
                                    <option value="NCBA Bank" {{ old('bank_name', $userDetails?->bank_name) == 'NCBA Bank' ? 'selected' : '' }}>NCBA Bank</option>
                                    <option value="Other" {{ old('bank_name', $userDetails?->bank_name) == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('bank_name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="bank_account_number">Bank Account Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('bank_account_number') is-invalid @enderror" 
                                       id="bank_account_number" name="bank_account_number" 
                                       placeholder="Enter bank account number"
                                       value="{{ old('bank_account_number', $userDetails?->bank_account_number) }}" required>
                                @error('bank_account_number')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="mobile_money_provider">Mobile Money Provider</label>
                                <select class="form-control @error('mobile_money_provider') is-invalid @enderror" 
                                        id="mobile_money_provider" name="mobile_money_provider">
                                    <option value="">Select provider</option>
                                    <option value="M-Pesa" {{ old('mobile_money_provider', $userDetails?->mobile_money_provider) == 'M-Pesa' ? 'selected' : '' }}>M-Pesa (Safaricom)</option>
                                    <option value="Airtel Money" {{ old('mobile_money_provider', $userDetails?->mobile_money_provider) == 'Airtel Money' ? 'selected' : '' }}>Airtel Money</option>
                                    <option value="T-Kash" {{ old('mobile_money_provider', $userDetails?->mobile_money_provider) == 'T-Kash' ? 'selected' : '' }}>T-Kash (Telkom)</option>
                                    <option value="Equitel" {{ old('mobile_money_provider', $userDetails?->mobile_money_provider) == 'Equitel' ? 'selected' : '' }}>Equitel</option>
                                </select>
                                @error('mobile_money_provider')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="mobile_money_account">Mobile Money Account <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('mobile_money_account') is-invalid @enderror" 
                                       id="mobile_money_account" name="mobile_money_account" 
                                       placeholder="Enter mobile money number"
                                       value="{{ old('mobile_money_account', $userDetails?->mobile_money_account) }}" required>
                                @error('mobile_money_account')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="total_monthly_income">Total Monthly Income (KES) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('total_monthly_income') is-invalid @enderror" 
                                       id="total_monthly_income" name="total_monthly_income" 
                                       placeholder="Total monthly income from all sources" step="0.01" min="0"
                                       value="{{ old('total_monthly_income', $userDetails?->total_monthly_income) }}" required>
                                @error('total_monthly_income')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="existing_loans">Existing Loans</label>
                                <textarea class="form-control @error('existing_loans') is-invalid @enderror" 
                                          id="existing_loans" name="existing_loans" 
                                          rows="3" placeholder="Describe any existing loans or credit commitments">{{ old('existing_loans', $userDetails?->existing_loans) }}</textarea>
                                @error('existing_loans')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Save Financial Information
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Profile Summary -->
@if($userDetails)
<div class="row">
    <div class="col-lg-12">
        <div class="card card-info card-outline">
            <div class="card-header">
                <h5 class="card-title m-0">Profile Summary</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-sm">
                        <tbody>
                            <tr>
                                <th style="width: 200px;">Employment Status</th>
                                <td>{!! $userDetails->employment_status_badge !!}</td>
                            </tr>
                            <tr>
                                <th>Employer</th>
                                <td>{{ $userDetails->employer_name ?? 'Not provided' }}</td>
                            </tr>
                            <tr>
                                <th>Job Title</th>
                                <td>{{ $userDetails->job_title ?? 'Not provided' }}</td>
                            </tr>
                            <tr>
                                <th>Employment Duration</th>
                                <td>{{ $userDetails->employment_duration ?? 'Not provided' }}</td>
                            </tr>
                            <tr>
                                <th>Monthly Income</th>
                                <td>{{ $userDetails->formatted_monthly_income }}</td>
                            </tr>
                            <tr>
                                <th>Total Monthly Income</th>
                                <td>{{ $userDetails->formatted_total_monthly_income }}</td>
                            </tr>
                            <tr>
                                <th>Bank</th>
                                <td>{{ $userDetails->bank_name ?? 'Not provided' }}</td>
                            </tr>
                            <tr>
                                <th>Mobile Money</th>
                                <td>{{ $userDetails->mobile_money_provider ?? 'Not provided' }}</td>
                            </tr>
                            <tr>
                                <th>Profile Completion</th>
                                <td>
                                    <div class="progress progress-sm">
                                        <div class="progress-bar bg-primary" 
                                             style="width: {{ $userDetails->profile_completion_percentage }}%">
                                        </div>
                                    </div>
                                    {{ $userDetails->profile_completion_percentage }}% Complete
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@endsection

@section('scripts')
<script>
    $(function () {
        // Auto-calculate total monthly income
        $('#monthly_income').on('input', function() {
            var monthlyIncome = parseFloat($(this).val()) || 0;
            $('#total_monthly_income').val(monthlyIncome);
        });

        // Show success message if forms are saved
        @if(session('employment_success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('employment_success') }}',
                timer: 3000
            });
        @endif

        @if(session('financial_success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('financial_success') }}',
                timer: 3000
            });
        @endif
    });
</script>
@endsection