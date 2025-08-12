<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loans;
use App\Models\UserDetails;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoansController extends Controller
{
    public function showApplicationForm()
    {
        return view('loans.apply');
    }

    public function apply(Request $request)
    {
        $validated = $request->validate([
            'purpose' => 'required|string|max:255',
            'amount' => 'required|numeric|min:1000|max:500000',
            'repayment_period' => 'required|in:30,90,180,360',
        ]);

        DB::beginTransaction();
        try {
            $user = Auth::user();
            
            // Calculate application fee (2% of requested amount)
            $applicationFee = $validated['amount'] * 0.02;
            
            // Determine approved amount based on eligibility
            $approvedAmount = $this->calculateEligibleAmount($user, $validated['amount']);
            
            $loan = Loans::create([
                'user_id' => $user->id,
                'purpose' => $validated['purpose'],
                'requested_amount' => $validated['amount'],
                'approved_amount' => $approvedAmount,
                'repayment_period' => $validated['repayment_period'],
                'application_fee' => $applicationFee,
                'status' => 'pending',
                'due_date' => now()->addDays($validated['repayment_period']),
            ]);

            DB::commit();

            return redirect()->route('loan.offer', $loan)
                ->with('success', 'Loan application submitted successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Loan application failed: ' . $e->getMessage());
        }
    }

    private function calculateEligibleAmount() {
        // dd(auth()->user()->monthly_income);

        $get_user_details=UserDetails::where('user_id',auth()->user()->id)->first();
        return min(request('amount'), $get_user_details->monthly_income * 3);
    }
    private function getEmploymentFactor(UserDetail $details)
    {
        if ($details->employment_type === 'permanent') {
            return 1.2;
        } elseif ($details->employment_type === 'contract') {
            return 0.9;
        } else {
            return 0.7; // Self-employed/temporary
        }
    }
    private function applyBusinessRules(User $user, $amount)
    {
        // 1. First-time borrower limit
        if ($user->loans()->count() === 0 && $amount > 50000) {
            $amount = 50000;
        }
        
        // 2. Round to nearest 1000
        $amount = round($amount / 1000) * 1000;
        
        // 3. Minimum loan amount
        return max($amount, 5000);
    }


    private function calculateCreditScore(User $user)
    {
        $score = 65; // Base score
        
        // Positive factors
        if ($user->loans()->where('status', 'repaid')->exists()) {
            $score += 15; // Good repayment history
        }
        
        if ($user->userDetails->employment_duration > 2) {
            $score += 10; // Long employment
        }
        
        // Negative factors
        if ($user->loans()->where('status', 'defaulted')->exists()) {
            $score -= 25; // Previous defaults
        }
        
        if ($user->latePayments()->count() > 0) {
            $score -= 15; // Late payments
        }

        return min(max($score, 30), 100); // Keep between 30-100
    }


    public function showOffer(Loans $loan)
    {
        if ($loan->user_id != Auth::id()) {
            abort(403);
        }

        return view('loans.offer', compact('loan'));
    }

    public function payFee(Loans $loan)
    {
        // In a real app, this would initiate M-Pesa STK push
        return view('loans.pay_fee', compact('loan'));
    }

    public function processPayment(Request $request, Loans $loan)
{
    $validated = $request->validate([
        'amount' => 'required|numeric|min:100|max:' . $loan->approved_amount * 1.15,
        'method' => 'required|in:mpesa,bank'
    ]);

    // Process payment (in a real app, this would call your payment processor)
    $payment = Payment::create([
        'user_id' => Auth::id(),
        'loan_id' => $loan->id,
        'amount' => $validated['amount'],
        'method' => $validated['method'],
        'status' => 'pending'
    ]);

    // In a real app, you would dispatch a job to process the payment
    // ProcessPayment::dispatch($payment);

    return redirect()->route('loan.status', $loan)
        ->with('success', 'Payment initiated. Please complete the payment process.');
}

    public function confirmPayment(Loans $loan)
    {
        // This would be called after successful payment callback from M-Pesa
        $loan->update([
            'fee_paid' => true,
            'status' => 'processing_disbursement',
        ]);

        return redirect()->route('loan.status', $loan)
            ->with('success', 'Payment confirmed. Loan disbursement in progress.');
    }
    public function showStatus(Loans $loan)
{
    // Ensure the loan belongs to the authenticated user
    if ($loan->user_id != Auth::id()) {
        abort(403, 'Unauthorized');
    }

    // Calculate repayment information
    $repaymentInfo = $this->calculateRepaymentInfo($loan);

    return view('loans.status', [
        'loan' => $loan,
        'repaymentInfo' => $repaymentInfo,
        'user' => Auth::user()
    ]);
}

private function calculateRepaymentInfo(Loans $loan)
{
    if (!in_array($loan->status, ['approved', 'disbursed', 'repaid'])) {
        return null;
    }

    $interestRate = 0.15; // 15% interest
    $totalRepayment = $loan->approved_amount * (1 + $interestRate);
    $dailyRepayment = $totalRepayment / 30; // Simplified calculation

    return [
        'total_repayment' => $totalRepayment,
        'daily_repayment' => $dailyRepayment,
        'due_date' => $loan->due_date,
        'days_remaining' => now()->diffInDays($loan->due_date, false),
        'interest_rate' => $interestRate * 100
    ];
}

    public function disburse(Loans $loan)
    {
        // This would be called by your system or admin to disburse funds
        DB::beginTransaction();
        try {
            $loan->update([
                'status' => 'disbursed',
                'disbursed_at' => now(),
            ]);

            // Credit user's loan wallet
            $user = $loan->user;
            $user->loan_balance += $loan->approved_amount;
            $user->save();

            DB::commit();

            return redirect()->route('loan.status', $loan)
                ->with('success', 'Loan has been disbursed to your account');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Disbursement failed: ' . $e->getMessage());
        }
    }
}