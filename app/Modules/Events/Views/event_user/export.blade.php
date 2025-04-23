@extends('backend.layouts.master')
@section ('scriptop')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('content')
<table>
    <thead>
        <tr>
            <th>STT</th>
            <th>Tên người dùng</th>
            <th>Vai trò</th>
            <th>Sự kiện</th>
        </tr>
    </thead>
    <tbody>
        @foreach($eventUsers as $index => $eu)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $eu->user->full_name }}</td>
            <td>{{ $eu->role->title ?? '' }}</td>
            <td>{{ $eu->event->title ?? '' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
