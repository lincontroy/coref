<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoansController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\LoanController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
use App\Http\Controllers\UserDetailsController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', 'App\Http\Controllers\UsersController@create')->name('register');
Route::get('/login', 'App\Http\Controllers\UsersController@login')->name('login');
Route::get('/logout', 'App\Http\Controllers\UsersController@logout')->name('logout');
Route::post('/register/post', 'App\Http\Controllers\UsersController@store')->name('store');
Route::post('/login/post', 'App\Http\Controllers\UsersController@loginpost')->name('login.post');

Route::get('/loan', 'App\Http\Controllers\UsersController@loan')->name('loan');

Route::get('/dashboard', 'App\Http\Controllers\UsersController@dashboard')->name('dashboard')->middleware('auth');
Route::get('/apply/loan', 'App\Http\Controllers\UsersController@applyloan')->name('apply.loan')->middleware('auth');
Route::post('/apply/loan/post', 'App\Http\Controllers\UsersController@applyloanpost')->name('apply.post')->middleware('auth');

Route::middleware(['auth'])->group(function () {
    Route::prefix('profile')->name('user-details.')->group(function () {
        Route::get('details', [UserDetailsController::class, 'index'])->name('index');
        Route::post('employment', [UserDetailsController::class, 'storeEmployment'])->name('employment.store');
        Route::post('financial', [UserDetailsController::class, 'storeFinancial'])->name('financial.store');
        Route::put('details', [UserDetailsController::class, 'update'])->name('update');
        Route::get('api/details', [UserDetailsController::class, 'show'])->name('show');
        Route::delete('details', [UserDetailsController::class, 'destroy'])->name('destroy');
    });
});

Route::middleware(['auth'])->prefix('loans')->group(function() {
    Route::get('/apply', [LoansController::class, 'showApplicationForm'])->name('loan.apply');
    Route::post('/apply', [LoansController::class, 'apply'])->name('loan.submit');
    Route::post('/{loan}/payment', [LoansController::class, 'processPayment'])->name('loan.payment');
    Route::get('/offer/{loan}', [LoansController::class, 'showOffer'])->name('loan.offer');
    Route::get('/pay-fee/{loan}', [LoansController::class, 'payFee'])->name('loan.pay_fee');
    Route::get('/confirm-payment/{loan}', [LoansController::class, 'confirmPayment'])->name('loan.confirm_payment');
    Route::post('/disburse/{loan}', [LoansController::class, 'disburse'])->name('loan.disburse');
    Route::get('/status/{loan}', [LoansController::class, 'showStatus'])->name('loan.status');
});

Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/loans', [LoanController::class, 'index'])->name('admin.loans.index');
    Route::get('/loans/{loan}', [LoanController::class, 'show'])->name('admin.loans.show');
    Route::post('/loans/{loan}/approve', [LoanController::class, 'approve'])->name('admin.loans.approve');
    Route::post('/loans/{loan}/reject', [LoanController::class, 'reject'])->name('admin.loans.reject');
    Route::post('/loans/{loan}/disburse', [LoanController::class, 'disburse'])->name('admin.loans.disburse');
});