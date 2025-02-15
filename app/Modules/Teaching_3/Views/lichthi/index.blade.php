@extends('backend.layouts.master')
@section('content')

 
 
    <h2 class="intro-y text-lg font-medium mt-10">
        Danh sách lịch thi
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            <a href="{{route('admin.lichthi.create')}}" class="btn btn-primary shadow-md mr-2">Thêm lịch thi</a>
            
            {{-- <div class="hidden md:block mx-auto text-slate-500">Hiển thị trang {{$hocphan->currentPage()}} trong {{$hocphan->lastPage()}} trang</div> --}}
            <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                <div class="w-56 relative text-slate-500">
                    <form action="{{route('admin.hocphan.search')}}" method = "get">
                        @csrf
                        <input type="text" name="datasearch" class="ipsearch form-control w-56 box pr-10" placeholder="Seach">
                        <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-lucide="search"></i> 
                    </form>
                </div>
            </div>
        </div>
        <!-- BEGIN: Data List -->
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            @if (Session::has('thongbao'))
                <div class="alert alert-success" id="success-alert">
                    {{ Session::get('thongbao') }}
                </div>
            @endif
            <script>
                // Kiểm tra xem thông báo có hiện diện không
                window.onload = function() {
                    var alert = document.getElementById('success-alert');
                    if (alert) {
                        // Đặt thời gian 4 giây trước khi ẩn
                        setTimeout(function() {
                            alert.style.display = 'none';
                        }, 4000); // 4000 ms = 4 giây
                    }
                };
            </script>
            <table class="table table-report -mt-2">
                <thead>
                    <tr>
                        <th class="whitespace-nowrap">PHÂN CÔNG</th>
                        <th class="whitespace-nowrap">BUỔI</th>                        
                        <th class="whitespace-nowrap">NGÀY 1</th>
                        <th class="whitespace-nowrap">NGÀY 2</th>
                        <th class="whitespace-nowrap">ĐỊA ĐIỂM</th>
                        <th class="whitespace-nowrap">TRẠNG THÁI</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lichthi as $item)
                    <tr class="intro-x">
                        @foreach ($teacher as $tc)
                            @foreach ($hocphan as $hp)
                                @if (($item->phanCong)->giangvien_id == $tc->id && ($item->phanCong)->hocphan_id == $hp->id)
                                    <td>
                                        <p target="_blank" href="" class="font-medium whitespace-nowrap">MGV: {{ $tc->mgv }} , Môn: {{ $hp->title }}</p> 
                                    </td>
                                @endif
                            @endforeach
                        @endforeach
                        <td>
                            <p target="_blank" href="" class="font-medium whitespace-nowrap">{{ ($item->buoi) }}</p> 
                        </td>
                        <td>
                            <p target="_blank" href="" class="font-medium whitespace-nowrap">{{ ($item->ngay1) }}</p> 
                        </td>
                        <td>
                            <p target="_blank" href="" class="font-medium whitespace-nowrap">{{ ($item->ngay2) }}</p> 
                        </td>
                        <td class="text-left">
                            @php
                                $dia_diem_thi = json_decode($item->dia_diem_thi, true);
                            @endphp
                            @if($dia_diem_thi && isset($dia_diem_thi['location']))
                                {{ implode(', ', array_map(fn($loc) => $diadiemList[$loc] ?? $loc, $dia_diem_thi['location'])) }}
                            @else
                                N/A
                            @endif
                        </td>
                        
                        <td class="table-report__action w-56">
                            <div class="flex justify-center items-center">
                                <a href="{{route('admin.lichthi.edit',$item->id)}}" class="flex items-center mr-3" href="javascript:;"> <i data-lucide="check-square" class="w-4 h-4 mr-1"></i> Edit </a>
                                <form action="{{route('admin.lichthi.destroy',$item->id)}}" method = "post">
                                    @csrf
                                    @method('DELETE')
                                    <a class="flex items-center text-danger dltBtn" data-id="{{$item->id}}" href="javascript:;" data-tw-toggle="modal" data-tw-target="#delete-confirmation-modal"> <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Delete </a>
                                </form>
                            </div>
                        </td>
                    </tr>

                    @endforeach
                    
                </tbody>
            </table>
            
        </div>
    </div>
    <!-- END: HTML Table Data -->
        <!-- BEGIN: Pagination -->
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
            <nav class="w-full sm:w-auto sm:mr-auto">
                {{-- {{$blogs->links('vendor.pagination.tailwind')}} --}}
            </nav>
           
        </div>
        <!-- END: Pagination -->
 
@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{asset('backend/assets/vendor/js/bootstrap-switch-button.min.js')}}"></script>
<script>
    $.ajaxSetup({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
    });
    $('.dltBtn').click(function(e)
    {
        var form=$(this).closest('form');
        var dataID = $(this).data('id');
        e.preventDefault();
        Swal.fire({
            title: 'Bạn có chắc muốn xóa không?',
            text: "Bạn không thể lấy lại dữ liệu sau khi xóa",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Vâng, tôi muốn xóa!'
            }).then((result) => {
            if (result.isConfirmed) {
                // alert(form);
                form.submit();
                // Swal.fire(
                // 'Deleted!',
                // 'Your file has been deleted.',
                // 'success'
                // );
            }
        });
    });
</script>
<script>
    $(".ipsearch").on('keyup', function (e) {
        e.preventDefault();
        if (e.key === 'Enter' || e.keyCode === 13) {
           
            // Do something
            var data=$(this).val();
            var form=$(this).closest('form');
            if(data.length > 0)
            {
                form.submit();
            }
            else
            {
                  Swal.fire(
                    'Không tìm được!',
                    'Bạn cần nhập thông tin tìm kiếm.',
                    'error'
                );
            }
        }
    });

    $("[name='toogle']").change(function() {
        var mode = $(this).prop('checked');
        var id=$(this).val();
        $.ajax({
            url:"{{route('admin.blog.status')}}",
            type:"post",
            data:{
                _token:'{{csrf_token()}}',
                mode:mode,
                id:id,
            },
            success:function(response){
                Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: response.msg,
                showConfirmButton: false,
                timer: 1000
                });
                console.log(response.msg);
            }
            
        });
  
});  
    
</script>
 
@endsection