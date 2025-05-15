@extends('backend.layouts.master')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3>Cấu hình VNPay</h3>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.vnpay.config.update') }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label>TMN Code</label>
                            <input type="text" name="vnp_TmnCode" class="form-control" 
                                value="{{ config('vnpay.vnp_TmnCode') }}" required>
                            <small class="text-muted">Mã website tại VNPAY: Q18D3Z7T</small>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label>Hash Secret</label>
                            <input type="text" name="vnp_HashSecret" class="form-control" 
                                value="{{ config('vnpay.vnp_HashSecret') }}" required>
                            <small class="text-muted">Chuỗi bí mật: Y9DM2RQVVHE1W4ZASKP3BLMFIG0BTVVS</small>
                        </div>

                        <div class="form-group mb-3">
                            <label>VNPay URL</label>
                            <input type="text" name="vnp_Url" class="form-control" 
                                value="{{ config('vnpay.vnp_Url') }}" required>
                            <small class="text-muted">URL môi trường test: https://sandbox.vnpayment.vn/paymentv2/vpcpay.html</small>
                        </div>

                        <div class="form-group mb-3">
                            <label>Return URL</label>
                            <input type="text" name="vnp_Returnurl" class="form-control" 
                                value="{{ config('vnpay.vnp_Returnurl') }}" required>
                            <small class="text-muted">URL nhận kết quả thanh toán từ VNPAY</small>
                        </div>

                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 