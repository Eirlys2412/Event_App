<?php

namespace App\Modules\VNPay\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VNPayTransaction extends Model
{
    use HasFactory;

    protected $table = 'vnpay_transactions';

    protected $fillable = [
        'order_id',
        'amount',
        'order_info',
        'status',
        'bank_code',
        'transaction_no'
    ];
} 