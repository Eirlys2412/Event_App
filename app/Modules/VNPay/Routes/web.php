<?php

use Illuminate\Support\Facades\Route;
use App\Modules\VNPay\Controllers\VNPayController;

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['web', 'auth']], function () {
    Route::get('vnpay/config', [VNPayController::class, 'config'])->name('vnpay.config');
    Route::post('vnpay/config/update', [VNPayController::class, 'updateConfig'])->name('vnpay.config.update');
    Route::get('vnpay/payment-form', [VNPayController::class, 'showPaymentForm'])->name('vnpay.payment-form');
});

// Routes cho thanh toán
Route::group(['middleware' => ['web']], function () {
    Route::post('vnpay/create-payment', [VNPayController::class, 'createPayment'])->name('vnpay.create-payment');
    Route::get('vnpay/return', [VNPayController::class, 'vnpayReturn'])->name('vnpay.return');
    Route::get('vnpay/ipn', [VNPayController::class, 'vnpayIPN'])->name('vnpay.ipn');
}); 