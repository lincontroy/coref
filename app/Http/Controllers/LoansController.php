<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loans;
use App\Models\UserDetails;
use App\Models\LoanApply;
use App\Models\BodaBoda;
use App\Models\Vehicle;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoansController extends Controller
{

    public function otherapply(Request $request)
{
    $request->validate([
        'purpose' => 'required|string|max:255',
        'requested_amount' => 'required|numeric|min:1000',
        'repayment_period' => 'required|integer|min:1',
        'notes' => 'nullable|string|max:500',
    ]);

    $loan = Loans::create([
        'user_id'          => Auth::id(),
        'purpose'          => $request->purpose,
        'requested_amount' => $request->requested_amount,
        'approved_amount'  => $request->requested_amount, // for now same
        'repayment_period' => $request->repayment_period,
        'application_fee'  => 0, // can calculate later
        'fee_paid'         => 0,
        'status'           => 'pending',
        'disbursed_at'     => null,
        'due_date'         => now()->addMonths($request->repayment_period),
    ]);


    $fee=($request->requested_amount*0.15);

    $user=auth()->user()->name;


    // Send SMS
    $phone = auth()->user()->phone;
    $phone = preg_replace('/^0/', '254', $phone); // format to 254

    $smsMessage = "Hello {$user}, your {$request->purpose} application for {$request->requested_amount} has been submitted successfully. Please pay KES " . number_format($fee) . " processing fee to till number 23456 to get the loan!";
    $this->sendSMSWithCurl($phone, $smsMessage);



    return redirect()->back()->with('success', 'Loan application submitted successfully! Check sms for further instructions.');
}


    public function bodaLoanApply($id)
{
    $boda = BodaBoda::findOrFail($id);

    // Calculate 15% deposit
    $deposit = $boda->price * 0.15;

    return view('loans.apply-bodaboda-loann', compact('boda', 'deposit'));
}

public function processBodaDeposit(Request $request, $id)
{
    $request->validate([
        'phone' => 'required|string|regex:/^254\d{9}$/',
    ]);

    $boda = BodaBoda::findOrFail($id);

    $deposit = $boda->price * 0.15;

    // Save loan application
    $loan = Loans::create([
        'user_id'          => Auth::id(),
        'purpose'          => "Boda Boda Purchase - " . $boda->name,
        'requested_amount' => $boda->price,
        'approved_amount'  => $boda->price, // full amount for now
        'repayment_period' => 24, // 24 months
        'application_fee'  => $deposit,
        'fee_paid'         => 0,
        'status'           => 'pending',
        'disbursed_at'     => null,
        'due_date'         => now()->addMonths(24),
    ]);

    // Trigger payment gateway (example placeholder)
    // $this->sendStkPush($request->phone, $deposit);

    return back()->with('success', "Please pay KES " . number_format($deposit) . " as your deposit. STK push sent to {$request->phone}.");
}

    public function applyBodaBodaLoan($id)
{
    $boda = BodaBoda::findOrFail($id);
    return view('loans.apply-bodaboda-loan', compact('boda'));
}

public function storeBodaBodaLoan(Request $request)
{
    $request->validate([
        'boda_id' => 'required|exists:boda_bodas,id',
        'loan_amount' => 'required|numeric|min:0',
        'loan_purpose' => 'nullable|string|max:255',
    ]);

    $user = Auth::user();
    $details = $user->getOrCreateUserDetails();
    $boda = BodaBoda::findOrFail($request->boda_id);

    // Processing fee = 1% of loan amount
    $loanAmount = $request->loan_amount;
    $processingFee = round($loanAmount * 0.01);

    // Save loan application
    $application = LoanApply::create([
        'user_id' => $user->id,
        'vehicle_id' => null,
        'full_name' => $user->name,
        'email' => $user->email,
        'phone' => $details->mobile_money_account ?? $user->phone,
        'id_number' => $details->id_number ?? '',
        'employment_status' => $details->employment_status ?? '',
        'company_name' => $details->employer_name ?? '',
        'monthly_income' => $details->monthly_income ?? 0,
        'loan_amount' => $loanAmount,
        'loan_purpose' => $request->loan_purpose,
        'boda_id' => $boda->id, // optional if you track boda separately
    ]);

    // Send SMS
    $phone = auth()->user()->phone;
    $phone = preg_replace('/^0/', '254', $phone); // format to 254

    $smsMessage = "Hello {$user->name}, your Boda Boda loan application for {$boda->name} has been submitted successfully. Please pay KES " . number_format($processingFee) . " processing fee to start your ride!";
    $this->sendSMSWithCurl($phone, $smsMessage);

    // Redirect with success message and fee
    $message = "Your Boda Boda loan application for {$boda->name} has cruised through successfully! 💰 Processing fee: KES " . number_format($processingFee) . ". Pay via M-Pesa Till 123456 using your ID number.";

    return redirect()->route('loan.bodaboda.apply', $boda->id)
                     ->with('success', $message)
                     ->with('processingFee', $processingFee);
}

    public function bodaDetails($id)
    {
        $boda = BodaBoda::findOrFail($id);
    
        return view('loans.boda-details', compact('boda'));
    }
    public function bodaboda()
{
    // Fetch all bodas from database
    $bodas = BodaBoda::all();

    return view('loans.bodabodas', compact('bodas'));
}

    public function loancarapply($id)
{
    $vehicle = Vehicle::findOrFail($id);
    return view('loans.apply-car-loan', compact('vehicle'));
}

    public function storeLoanApplication(Request $request)
    {
        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'loan_amount' => 'required|numeric|min:0',
            'loan_purpose' => 'nullable|string|max:255',
        ]);
    
        $user = Auth::user();
        $details = $user->getOrCreateUserDetails();
        $vehicle = Vehicle::findOrFail($request->vehicle_id);
    
        $loanAmount = (float) $request->loan_amount; // cast to float
        $processingFee = round($loanAmount * 0.01);  // 1% of loan
    
        // Save application
        $application = LoanApply::create([
            'user_id' => $user->id,
            'vehicle_id' => $vehicle->id,
            'full_name' => $user->name,
            'email' => $user->email,
            'phone' => $details->mobile_money_account ?? '',
            'id_number' => $details->id_number ?? '',
            'employment_status' => $details->employment_status ?? '',
            'company_name' => $details->employer_name ?? '',
            'monthly_income' => $details->monthly_income ?? 0,
            'loan_amount' => $loanAmount,
            'loan_purpose' => $request->loan_purpose,
        ]);
    
        $message = "Your application for {$vehicle->name} has cruised through successfully! 
To shift gears and start the processing engine, a gentle **KES " . number_format($processingFee) . "** processing fee is needed. 
Hop onto M-Pesa and pay to Till Number: 123456 using your ID number as reference for a smooth ride.";

$result = $this->sendSMSWithCurl(auth()->user()->phone, $message);

// return $result;
return redirect()->route('car.loan.apply.loan', $vehicle->id)
                 ->with('success', $message)
                 ->with('vehicleName', $vehicle->name);

    }

    
    public function sendSMSWithCurl($phoneNumber, $message)
    {
        $url = 'https://ujumbesms.co.ke/api/messaging'; // Adjust if necessary
    
        $headers = [
            "X-Authorization: YTBkOTE3OGNmNDg3ZDE2Y2NiMGIzNjg1ZTc0Mzg2",
            "email: developer@automationeye.com",
            "Cache-Control: no-cache",
            "Content-Type: application/json"
        ];
    
        $jsonBody = json_encode([
            "data" => [
                [
                    "message_bag" => [
                        "numbers" => $phoneNumber,  // Use dynamic phone number
                        "message" => $message,      // Use dynamic message
                        "sender" => "DEPTHSMS"
                    ]
                ]
            ]
        ]);
    
        $ch = curl_init($url);
    
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonBody);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
        try {
            $response = curl_exec($ch);
    
            if ($response === false) {
                throw new \Exception(curl_error($ch));
            }
    
            return $response;
        } catch (\Exception $e) {
            return $e->getMessage();
        } finally {
            curl_close($ch);
        }
    }
    


    public function carapply($id)
{
    // Hardcoded vehicle data (later move to DB)
    $vehicles = Vehicle::orderBy('id','DESC')->get();

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
    $vehicles = Vehicle::orderBy('id','DESC')->get();

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
    $vehicles = Vehicle::orderBy('id','DESC')->get();

    return view('loans.cars', compact('vehicles'));
}

public function carDetails($id)
{

    // dd($id);
    // Normally you'd fetch from DB
    $vehicles = Vehicle::orderBy('id','DESC')->get();

    $vehicle = $vehicles[$id] ?? null;

    if (!$vehicle) {
        abort(404);
    }
    // dd($vehicle);

    return view('loans.car-details', compact('vehicle'));
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