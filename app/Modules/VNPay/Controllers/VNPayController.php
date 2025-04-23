<?php

namespace App\Modules\VNPay\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\VNPay\Models\VNPayTransaction;
use Illuminate\Support\Facades\Log;

class VNPayController extends Controller
{
    protected $vnp_TmnCode; // Mã website tại VNPAY 
    protected $vnp_HashSecret; // Chuỗi bí mật
    protected $vnp_Url; // URL thanh toán
    protected $vnp_Returnurl; // URL trả về

    public function __construct()
    {
        // Khởi tạo các giá trị từ config
        $this->vnp_TmnCode = config('vnpay.vnp_TmnCode');
        $this->vnp_HashSecret = config('vnpay.vnp_HashSecret');
        $this->vnp_Url = config('vnpay.vnp_Url');
        $this->vnp_Returnurl = config('vnpay.vnp_Returnurl');
    }

    public function config()
    {
        $active_menu = "vnpay_config";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Cấu hình VNPay</li>';

        return view('VNPay::config', compact('active_menu', 'breadcrumb'));
    }

    public function createPayment(Request $request)
    {
        $vnp_TxnRef = uniqid(); // Mã đơn hàng unique
        $vnp_Amount = $request->amount * 100; // Nhân 100 vì VNPay yêu cầu
        
        // Tạo dữ liệu gửi đến VNPay
        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => env('VNPAY_TMN_CODE'),
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => request()->ip(),
            "vnp_Locale" => 'vn',
            "vnp_OrderInfo" => $request->order_desc,
            "vnp_OrderType" => $request->order_type ?? 'billpayment',
            "vnp_ReturnUrl" => env('VNPAY_RETURN_URL'),
            "vnp_TxnRef" => $vnp_TxnRef,
        );

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        // Tạo URL thanh toán VNPay
        $vnpSecureHash = hash_hmac('sha512', $hashdata, env('VNPAY_HASH_SECRET'));
        $vnp_Url = env('VNPAY_URL') . "?" . $query;
        $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;

        // Lưu thông tin giao dịch vào database
        VNPayTransaction::create([
            'order_id' => $vnp_TxnRef,
            'amount' => $request->amount,
            'order_info' => $request->order_desc,
            'status' => 'pending'
        ]);

        return redirect($vnp_Url);
    }

    public function vnpayReturn(Request $request)
    {
        Log::info('VNPay Return Data:', $request->all());
        
        try {
            $inputData = $request->all();
            $vnp_SecureHash = $inputData['vnp_SecureHash'] ?? '';
            unset($inputData['vnp_SecureHash']);
            
            // Sắp xếp dữ liệu theo key
            ksort($inputData);
            
            // Tạo chuỗi hash data
            $i = 0;
            $hashData = "";
            foreach ($inputData as $key => $value) {
                if ($i == 1) {
                    $hashData .= '&' . urlencode($key) . "=" . urlencode($value);
                } else {
                    $hashData = urlencode($key) . "=" . urlencode($value);
                    $i = 1;
                }
            }

            Log::info('Hash Data: ' . $hashData);
            
            $secureHash = hash_hmac('sha512', $hashData, env('VNPAY_HASH_SECRET'));
            Log::info('Generated Hash: ' . $secureHash);
            Log::info('VNPay Hash: ' . $vnp_SecureHash);

            if ($secureHash === $vnp_SecureHash) {
                $vnp_ResponseCode = $inputData['vnp_ResponseCode'];
                $transaction = VNPayTransaction::where('order_id', $inputData['vnp_TxnRef'])->first();

                if ($transaction && $vnp_ResponseCode == '00') {
                    $transaction->status = 'completed';
                    $transaction->bank_code = $inputData['vnp_BankCode'] ?? null;
                    $transaction->transaction_no = $inputData['vnp_TransactionNo'] ?? null;
                    $transaction->save();

                    return view('VNPay::return', [
                        'success' => true,
                        'message' => 'Thanh toán thành công',
                        'active_menu' => 'vnpay_transactions'
                    ]);
                }
            }

            return view('VNPay::return', [
                'success' => false,
                'message' => 'Thanh toán thất bại',
                'active_menu' => 'vnpay_transactions'
            ]);

        } catch (\Exception $e) {
            Log::error('VNPay Return Error: ' . $e->getMessage());
            return view('VNPay::return', [
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage(),
                'active_menu' => 'vnpay_transactions'
            ]);
        }
    }

    public function vnpayIPN(Request $request)
    {
        $inputData = array();
        $returnData = array();

        foreach ($_GET as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }

        $vnp_SecureHash = $inputData['vnp_SecureHash'];
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $this->vnp_HashSecret);
        $vnpTranId = $inputData['vnp_TransactionNo'];
        $vnp_BankCode = $inputData['vnp_BankCode'];
        $vnp_Amount = $inputData['vnp_Amount']/100;
        $Status = 0;
        try {
            if ($secureHash == $vnp_SecureHash) {
                $transaction = VNPayTransaction::where('order_id', $inputData['vnp_TxnRef'])->first();
                if ($transaction != NULL) {
                    if($transaction->amount == $vnp_Amount) 
                    {
                        if ($transaction->status == 'pending') {
                            $Status = 1;
                            $transaction->status = 'completed';
                            $transaction->save();
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }

        if($Status == 1)
        {
            $returnData['RspCode'] = '00';
            $returnData['Message'] = 'Confirm Success';
        }
        else 
        {
            $returnData['RspCode'] = '99';
            $returnData['Message'] = 'Confirm Fail';
        }

        return response()->json($returnData);
    }

    public function updateConfig(Request $request)
    {
        $request->validate([
            'vnp_TmnCode' => 'required|string',
            'vnp_HashSecret' => 'required|string',
            'vnp_Url' => 'required|url',
            'vnp_Returnurl' => 'required|url'
        ]);

        // Cập nhật file .env
        $this->updateEnvFile([
            'VNPAY_TMN_CODE' => $request->vnp_TmnCode,
            'VNPAY_HASH_SECRET' => $request->vnp_HashSecret,
            'VNPAY_URL' => $request->vnp_Url,
            'VNPAY_RETURN_URL' => $request->vnp_Returnurl
        ]);

        return redirect()->back()->with('success', 'Cập nhật cấu hình VNPay thành công');
    }

    private function updateEnvFile($data)
    {
        $envFile = app()->environmentFilePath();
        $contentArray = collect(file($envFile, FILE_IGNORE_NEW_LINES));

        foreach ($data as $key => $value) {
            $contentArray->transform(function ($item) use ($key, $value) {
                if (strpos($item, $key . '=') === 0) {
                    return $key . '=' . $value;
                }
                return $item;
            });

            if (!$contentArray->contains(function ($item) use ($key) {
                return strpos($item, $key . '=') === 0;
            })) {
                $contentArray->push($key . '=' . $value);
            }
        }

        file_put_contents($envFile, $contentArray->implode("\n"));
    }

    public function showPaymentForm()
    {
        $active_menu = "vnpay_transactions";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Tạo thanh toán VNPay</li>';

        return view('VNPay::payment-form', compact('active_menu', 'breadcrumb'));
    }
} 