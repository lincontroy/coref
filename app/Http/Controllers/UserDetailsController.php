<?php

namespace App\Http\Controllers;

use App\Models\UserDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserDetailsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the user details dashboard
     */
    public function index()
    {
        $userDetails = Auth::user()->userDetails;
        
        return view('user-details.index', compact('userDetails'));
    }

    /**
     * Store employment details
     */
    public function storeEmployment(Request $request)
    {
        $validated = $request->validate([
            'employment_status' => ['required', 'in:employed,self-employed,unemployed'],
            'employer_name' => ['nullable', 'string', 'max:255'],
            'job_title' => ['required', 'string', 'max:255'],
            'employment_duration' => ['nullable', 'string', 'max:255'],
            'monthly_income' => ['required', 'numeric', 'min:0'],
            'other_income_sources' => ['nullable', 'string'],
        ]);

        $userDetails = Auth::user()->getOrCreateUserDetails();
        
        $userDetails->update([
            ...$validated,
            'employment_details_completed' => true,
        ]);

        return redirect()->route('user-details.index')
            ->with('employment_success', 'Employment details saved successfully!');
    }

    /**
     * Store financial details
     */
    public function storeFinancial(Request $request)
    {
        $validated = $request->validate([
            'bank_name' => ['nullable', 'string', 'max:255'],
            'bank_account_number' => ['required', 'string', 'max:255'],
            'mobile_money_provider' => ['nullable', 'string', 'max:255'],
            'mobile_money_account' => ['required', 'string', 'max:255'],
            'total_monthly_income' => ['required', 'numeric', 'min:0'],
            'existing_loans' => ['nullable', 'string'],
        ]);

        $userDetails = Auth::user()->getOrCreateUserDetails();
        
        $userDetails->update([
            ...$validated,
            'financial_details_completed' => true,
        ]);

        return redirect()->route('user-details.index')
            ->with('financial_success', 'Financial information saved successfully!');
    }

    /**
     * Update user details
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            // Employment fields
            'employment_status' => ['nullable', 'in:employed,self-employed,unemployed'],
            'employer_name' => ['nullable', 'string', 'max:255'],
            'job_title' => ['nullable', 'string', 'max:255'],
            'employment_duration' => ['nullable', 'string', 'max:255'],
            'monthly_income' => ['nullable', 'numeric', 'min:0'],
            'other_income_sources' => ['nullable', 'string'],
            
            // Financial fields
            'bank_name' => ['nullable', 'string', 'max:255'],
            'bank_account_number' => ['nullable', 'string', 'max:255'],
            'mobile_money_provider' => ['nullable', 'string', 'max:255'],
            'mobile_money_account' => ['nullable', 'string', 'max:255'],
            'total_monthly_income' => ['nullable', 'numeric', 'min:0'],
            'existing_loans' => ['nullable', 'string'],
        ]);

        $userDetails = Auth::user()->getOrCreateUserDetails();
        $userDetails->update($validated);

        return redirect()->route('user-details.index')
            ->with('success', 'Profile updated successfully!');
    }

    /**
     * Get user details as JSON (for API or AJAX calls)
     */
    public function show()
    {
        $userDetails = Auth::user()->userDetails;
        
        if (!$userDetails) {
            return response()->json([
                'message' => 'User details not found',
                'data' => null
            ], 404);
        }

        return response()->json([
            'message' => 'User details retrieved successfully',
            'data' => [
                'employment' => [
                    'status' => $userDetails->employment_status,
                    'employer' => $userDetails->employer_name,
                    'job_title' => $userDetails->job_title,
                    'duration' => $userDetails->employment_duration,
                    'monthly_income' => $userDetails->monthly_income,
                    'other_sources' => $userDetails->other_income_sources,
                    'completed' => $userDetails->employment_details_completed,
                ],
                'financial' => [
                    'bank_name' => $userDetails->bank_name,
                    'bank_account' => $userDetails->bank_account_number,
                    'mobile_money_provider' => $userDetails->mobile_money_provider,
                    'mobile_money_account' => $userDetails->mobile_money_account,
                    'total_monthly_income' => $userDetails->total_monthly_income,
                    'existing_loans' => $userDetails->existing_loans,
                    'completed' => $userDetails->financial_details_completed,
                ],
                'profile_completion' => $userDetails->profile_completion_percentage,
                'formatted' => [
                    'employment_status_badge' => $userDetails->employment_status_badge,
                    'monthly_income' => $userDetails->formatted_monthly_income,
                    'total_monthly_income' => $userDetails->formatted_total_monthly_income,
                ]
            ]
        ]);
    }

    /**
     * Delete user details
     */
    public function destroy()
    {
        $userDetails = Auth::user()->userDetails;
        
        if ($userDetails) {
            $userDetails->delete();
            return redirect()->route('user-details.index')
                ->with('success', 'User details deleted successfully!');
        }

        return redirect()->route('user-details.index')
            ->with('error', 'No user details found to delete.');
    }
}