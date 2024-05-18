<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });


Route::post('/registration', [UserController::class, 'registration'])->name('registration');
Route::post('/login', [UserController::class, 'login'])->name('login');
Route::post('/send-otp', [UserController::class, 'sendOtp']);
Route::post('/verify-otp', [UserController::class, 'verifyOtp']);







Route::view('/registration', 'page.registration');
Route::view('/login', 'page.login');
