{{-- @extends('backend.layouts.master')

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
                        <td>{{ $diemdanh->thoikhoabieu->phancong->giangvien->mgv ?? 'N/A' }}</td>
                        <td>{{ $diemdanh->thoikhoabieu->phancong->hocphan->title ?? 'N/A' }}</td>
                        <td>{{ $diemdanh->thoikhoabieu->diaDiem->title ?? 'N/A' }}</td>
                        <td>{{ $diemdanh->thoikhoabieu->buoi ?? 'N/A' }}</td>
                        <td>{{ $diemdanh->thoikhoabieu->ngay ?? 'N/A' }}</td>
                        <td>{{ $diemdanh->thoikhoabieu->tietdau ?? 'N/A' }}</td>
                        <td>{{ $diemdanh->thoikhoabieu->tietcuoi ?? 'N/A' }}</td>
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
                    @php
                        $studentList = json_decode($diemdanh->student_list, true) ?? []; // Giải mã JSON và kiểm tra null
                    @endphp

                    @if (!empty($studentList))
                        @foreach ($studentList as $studentData)
                            @php
                                // Tìm sinh viên trong danh sách đã tải từ Controller
                                $student = $students->firstWhere('id', $studentData);
                            @endphp
                            <tr class="intro-x">
                                <td>{{ $student ? $student->user->full_name : 'Không xác định' }}</td>
                                <td>{{ $studentData['time'] ?? 'Không có dữ liệu' }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="2" class="text-center">Không có dữ liệu điểm danh.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection --}}
