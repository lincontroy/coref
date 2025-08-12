<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loans;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use DB;

class LoanController extends Controller
{
    public function index()
    {
        $loans = Loans::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.loans.index', compact('loans'));
    }

    public function show(Loans $loan)
    {
        return view('admin.loans.show', compact('loan'));
    }

    public function approve(Request $request, Loans $loan)
    {
        $validated = $request->validate([
            'approved_amount' => 'required|numeric|min:1000|max:'.$loan->requested_amount,
        ]);

        $loan->update([
            'approved_amount' => $validated['approved_amount'],
            'status' => 'approved',
            'due_date' => now()->addDays($loan->repayment_period),
        ]);

        return redirect()->route('admin.loans.show', $loan)
            ->with('success', 'Loan approved successfully');
    }

    public function reject(Loans $loan)
    {
        $loan->update([
            'status' => 'rejected',
            'rejected_at' => now(),
        ]);

        return redirect()->route('admin.loans.show', $loan)
            ->with('success', 'Loan rejected successfully');
    }

    public function disburse(Loans $loan)
    {
        DB::transaction(function () use ($loan) {
            $loan->update([
                'status' => 'disbursed',
                'disbursed_at' => now(),
            ]);

            $user=User::where('id',$loan->user_id)->first();

            // $user_balance=$user->wallet;

            $user->increment('wallet', $loan->approved_amount);
        });

        return redirect()->route('admin.loans.show', $loan)
            ->with('success', 'Loan disbursed to user wallet');
    }
}