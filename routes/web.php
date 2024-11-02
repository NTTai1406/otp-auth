<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('login');
});

Route::middleware(['web'])->group(function () {
    // Route cho đăng nhập không cần OTP
    Route::get('/login', function () {
        return view('login');
    })->name('login');

    // Route xử lý OTP
    Route::post('/otp-verified', function (Request $request) {
        $request->session()->put('otp_verified', true);
        return response()->json(['success' => true]);
    });

    // Route cho trang index và tạo địa chỉ với xác thực OTP
    Route::match(['get', 'post'], '/index', [\App\Http\Controllers\AddressController::class, 'index'])
        ->middleware(\App\Http\Middleware\OtpVerified::class)->name('index');

    Route::post('/index/create', [\App\Http\Controllers\AddressController::class, 'storeAddress'])
        ->middleware(\App\Http\Middleware\OtpVerified::class)->name('storeAddress');
});

