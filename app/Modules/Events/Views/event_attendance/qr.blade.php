@extends('backend.layouts.master')

@section('content')
<div class="container">
    <h1>QR Code for Event Attendance</h1>
    <div>{!! $qrCode !!}</div>
    <p>Scan this QR code to check in to the event.</p>
</div>
@endsection 