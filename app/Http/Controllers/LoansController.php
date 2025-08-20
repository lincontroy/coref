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

    public function carapply($id)
{
    // Hardcoded vehicle data (later move to DB)
    $vehicles = [
        1 => ['id'=>'1', 'name' => 'Toyota Corolla 2020', 'price' => 1200000],
        2 => ['id'=>'2','name' => 'Nissan X-Trail 2019', 'price' => 1800000],
        3 => ['id'=>'3','name' => 'Mazda Demio 2018', 'price' => 850000],
    ];

    $vehicle = $vehicles[$id] ?? null;

    if (!$vehicle) {
        abort(404);
    }

    // Calculate 15% deposit
    $deposit = $vehicle['price'] * 0.15;

    return view('loans.carapply', compact('vehicle', 'deposit'));
}

public function processPayment(Request $request, $id)
{
    // get vehicle price (in real app from DB)
    $vehicles = [
        1 => ['name' => 'Toyota Corolla 2020', 'price' => 1200000],
        2 => ['name' => 'Nissan X-Trail 2019', 'price' => 1800000],
        3 => ['name' => 'Mazda Demio 2018', 'price' => 850000],
    ];

    $vehicle = $vehicles[$id] ?? null;
    if (!$vehicle) abort(404);

    $deposit = $vehicle['price'] * 0.15;

    $loan = Loans::create([
        'user_id'          => Auth::id(),  // logged-in user
        'purpose'          => "Vehicle Purchase - " . $vehicle['name'],
        'requested_amount' => $vehicle['price'],
        'approved_amount'  => $vehicle['price'], // for now approve full price
        'repayment_period' => 24, // e.g. 24 months, adjust as needed
        'application_fee'  => $deposit, // depends on your business logic
        'fee_paid'         => 0,
        'status'           => 'pending', // until payment confirmed
        'disbursed_at'     => null,
        'due_date'         => now()->addMonths(24),
    ]);

    // 👉 Here you trigger your payment gateway (e.g. STK Push for M-Pesa)
    // Example placeholder:
    // $this->sendStkPush($request->phone, $deposit);

    return back()->with('success', "Please pay KES " . number_format($deposit) . " as your deposit. STK sent");
}

    
    public function showApplicationForm()
    {
        return view('loans.apply');
    }
    public function cars()
{
    // Example vehicle data (later you can fetch from DB)
    $vehicles = [
        [
            'id' => 1,
            'name' => 'Toyota Corolla 2020',
            'price' => 'KES 1,200,000',
            'image' => 'images/cars/toyota-corolla.jpg',
            'description' => 'Reliable and fuel efficient sedan perfect for city and long drives.'
        ],
        [
            'id' => 2,
            'name' => 'Nissan X-Trail 2019',
            'price' => 'KES 1,800,000',
            'image' => 'images/cars/nissan-xtrail.jpg',
            'description' => 'Spacious SUV with modern features and off-road capability.'
        ],
        [
            'id' => 3,
            'name' => 'Mazda Demio 2018',
            'price' => 'KES 850,000',
            'image' => 'images/cars/mazda-demio.jpg',
            'description' => 'Compact and stylish hatchback, ideal for daily commuting.'
        ]
    ];

    return view('loans.cars', compact('vehicles'));
}

public function carDetails($id)
{

    // dd($id);
    // Normally you'd fetch from DB
    $vehicles = [
        1 => [
            'id' => 1,
            'name' => 'Toyota Corolla 2020',
            'price' => 'KES 1,200,000',
            'image' => 'images/cars/toyota-corolla.jpg',
            'description' => 'Reliable and fuel efficient sedan perfect for city and long drives. Financing available with flexible repayment terms.'
        ],
        2 => [
            'id' => 2,
            'name' => 'Nissan X-Trail 2019',
            'price' => 'KES 1,800,000',
            'image' => 'images/cars/nissan-xtrail.jpg',
            'description' => 'Spacious SUV with modern features and off-road capability. Available with competitive car loan packages.'
        ],
        3 => [
            'id' => 3,
            'name' => 'Mazda Demio 2018',
            'price' => 'KES 850,000',
            'image' => 'images/cars/mazda-demio.jpg',
            'description' => 'Compact and stylish hatchback, ideal for daily commuting. Easy financing options available.'
        ],
    ];

    $vehicle = $vehicles[$id] ?? null;

    if (!$vehicle) {
        abort(404);
    }
    // dd($vehicle);

    return view('loans.car-details', compact('vehicle'));
}

    public function bodaboda()
    {
        return view('loans.bodaboda');
    }

    public function education()
    {
        return view('loans.education');
    }

    public function kilimo()
    {
        return view('loans.kilimo');
    }

    public function emergency()
    {
        return view('loans.emergency');
    }

    public function business()
    {
        return view('loans.business');
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