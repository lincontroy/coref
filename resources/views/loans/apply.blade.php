
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap Bundle JS (with Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

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
                            <div class="card text-center h-100 p-4 category-card"
                                data-bs-toggle="modal"
                                data-bs-target="#loanModal"
                                data-loan="Education Loan">
                                <i class="fas fa-graduation-cap fa-3x mb-3 text-info"></i>
                                <h5>Education Loan</h5>
                                <p class="small text-muted">Finance school & university fees with ease</p>
                            </div>
                        </div>

                        {{-- Kilimo Loan --}}
                        <div class="col-md-4">
                            <div class="card text-center h-100 p-4 category-card"
                                data-bs-toggle="modal"
                                data-bs-target="#loanModal"
                                data-loan="Kilimo Loan">
                                <i class="fas fa-tractor fa-3x mb-3 text-warning"></i>
                                <h5>Kilimo Loan</h5>
                                <p class="small text-muted">Support for farmers & agribusiness projects</p>
                            </div>
                        </div>

                        {{-- Emergency Loan --}}
                        <div class="col-md-4">
                            <div class="card text-center h-100 p-4 category-card"
                                data-bs-toggle="modal"
                                data-bs-target="#loanModal"
                                data-loan="Emergency Loan">
                                <i class="fas fa-ambulance fa-3x mb-3 text-danger"></i>
                                <h5>Emergency Loan</h5>
                                <p class="small text-muted">Quick funds when you need them most</p>
                            </div>
                        </div>

                        {{-- Business Loan --}}
                        <div class="col-md-4">
                            <div class="card text-center h-100 p-4 category-card"
                                data-bs-toggle="modal"
                                data-bs-target="#loanModal"
                                data-loan="Business Loan">
                                <i class="fas fa-briefcase fa-3x mb-3 text-dark"></i>
                                <h5>Business Loan</h5>
                                <p class="small text-muted">Expand and grow your business operations</p>
                            </div>
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

<!-- Loan Application Modal -->
<div class="modal fade" id="loanModal" tabindex="-1" aria-labelledby="loanModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form method="POST" action="{{ route('loan.otherapply') }}">
        @csrf
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="loanModalLabel">Apply for Loan</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="purpose" id="loanPurpose">
  
            <div class="mb-3">
              <label class="form-label">Requested Amount</label>
              <input type="number" name="requested_amount" class="form-control" required>
            </div>
  
            <div class="mb-3">
              <label class="form-label">Repayment Duration (Months)</label>
              <input type="number" name="repayment_period" class="form-control" required>
            </div>
  
            <div class="mb-3">
              <label class="form-label">Additional Notes (optional)</label>
              <textarea name="notes" class="form-control"></textarea>
            </div>
  
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Submit Application</button>
          </div>
        </div>
      </form>
    </div>
  </div>
  <script>
    document.addEventListener("DOMContentLoaded", function () {
        var loanModal = document.getElementById('loanModal');
        loanModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var loanType = button.getAttribute('data-loan');
            document.getElementById('loanPurpose').value = loanType;
            document.getElementById('loanModalLabel').innerText = "Apply for " + loanType;
        });
    });
    </script>
      
@endsection
