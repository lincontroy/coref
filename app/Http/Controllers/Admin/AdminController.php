<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loans;
use App\Models\User;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        $metrics = [
            'total_loans' => Loans::count(),
            'pending_loans' => Loans::where('status', 'pending')->count(),
            'approved_loans' => Loans::where('status', 'approved')->count(),
            'disbursed_loans' => Loans::where('status', 'disbursed')->count(),
            'total_amount' => Loans::where('status', 'disbursed')->sum('approved_amount'),
            'new_users' => User::whereDate('created_at', Carbon::today())->count(),
        ];

        $recentLoans = Loans::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('metrics', 'recentLoans'));
    }
}