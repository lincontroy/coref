<?php

use Illuminate\Support\Facades\Route;

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