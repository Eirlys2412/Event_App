@extends('backend.layouts.master')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Kết quả thanh toán</div>

                <div class="card-body">
                    @if($success)
                        <div class="alert alert-success">
                            {{ $message }}
                        </div>
                    @else
                        <div class="alert alert-danger">
                            {{ $message }}
                        </div>
                    @endif

                    <a href="{{ route('admin.vnpay.payment-form') }}" class="btn btn-primary">Quay lại</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 