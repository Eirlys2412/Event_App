@extends('backend.layouts.master')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Tạo thanh toán VNPay</div>

                <div class="card-body">
                    <form action="{{ route('vnpay.create-payment') }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label>Số tiền</label>
                            <input type="number" name="amount" class="form-control" required>
                        </div>

                        <div class="form-group mb-3">
                            <label>Nội dung thanh toán</label>
                            <input type="text" name="order_desc" class="form-control" required>
                        </div>

                        <input type="hidden" name="order_type" value="billpayment">
                        <input type="hidden" name="language" value="vn">
                        <input type="hidden" name="bank_code" value="">

                        <button type="submit" class="btn btn-primary">Thanh toán VNPay</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 