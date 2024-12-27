@extends('backend.layouts.master')

@section('content')
    <h2 class="intro-y text-lg font-medium mt-10">
        Chi tiết thời khóa biểu điểm danh
    </h2>
    
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="table table-report -mt-2">
                <thead>
                    <tr>
                        <th class="whitespace-nowrap">MÃ GIẢNG VIÊN</th>
                        <th class="whitespace-nowrap">MÔN HỌC</th>
                        <th class="whitespace-nowrap">ĐỊA ĐIỂM</th>
                        <th class="whitespace-nowrap">BUỔI</th>
                        <th class="whitespace-nowrap">NGÀY</th>
                        <th class="whitespace-nowrap">TIẾT ĐẦU</th>
                        <th class="whitespace-nowrap">TIẾT CUỐI</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="intro-x">
                         <td>
                            <p> {{ $diemdanh->thoikhoabieu->phancong->giangvien->mgv }}</p>
                        </td>
                        <td>
                            <p> {{ $diemdanh->thoikhoabieu->phancong->hocphan->title }}</p>
                        </td>
                        <td>
                            <p> {{ $diemdanh->thoikhoabieu->diaDiem->title }}</p>
                        </td>
                        <td>
                            <p> {{ $diemdanh->thoikhoabieu->buoi }}</p>
                        </td>
                        <td>
                            <p> {{ $diemdanh->thoikhoabieu->ngay }}</p>
                        </td>
                        <td>
                            <p> {{ $diemdanh->thoikhoabieu->tietdau }}</p>
                        </td>
                        <td>
                            <p> {{ $diemdanh->thoikhoabieu->tietcuoi }}</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <h2 class="intro-y text-lg font-medium mt-10">
        Chi tiết danh sách người học
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="table table-report -mt-2">
                <thead>
                    <tr>
                        <th class="whitespace-nowrap">NGƯỜI HỌC</th>
                        <th class="whitespace-nowrap">THỜI GIAN ĐIỂM DANH</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="intro-x">
                        <td>                        
                            <p>
                                @php
                                $userList = json_decode($diemdanh->user_list); // Giải mã JSON từ user_list
                                @endphp
                                @foreach ($userList as $user)
                                    @foreach ($users as $data)
                                        @if ($user->user_id == $data->id)
                                            <span>{{ $data->full_name }}</p>
                                        @endif
                                    @endforeach
                                @endforeach
                            </p>
                        </td>
                        <td>                        
                            <p>
                                @php
                                $userList = json_decode($diemdanh->user_list); // Giải mã JSON từ user_list
                                @endphp
                                @foreach ($userList as $user)
                                    <span>{{ $user->time }}</p>
                                @endforeach
                            </p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
