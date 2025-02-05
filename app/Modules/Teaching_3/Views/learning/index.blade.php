@extends('backend.layouts.master')
@section('content')
    <h2 class="intro-y text-lg font-medium mt-10">
        Danh sách người dùng đang học
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            <a href="{{route('admin.learning.create')}}" class="btn btn-primary shadow-md mr-2">Thêm người dùng</a>
            <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                <div class="w-56 relative text-slate-500">
                    <form action="{{route('admin.hocphan.search')}}" method = "get">
                        @csrf
                        <input type="text" name="datasearch" class="ipsearch form-control w-56 box pr-10" placeholder="Tìm kiếm">
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

            <table class="table table-report -mt-2">
                <thead>
                    <tr>
                        <th class="whitespace-nowrap text-center">NGƯỜI HỌC</th>
                        <th class="whitespace-nowrap text-center">PHÂN CÔNG</th>    
                        <th class="whitespace-nowrap text-center">NỘI DUNG</th>    
                        <th class="whitespace-nowrap text-center">THỜI GIAN HỌC</th>    
                        <th class="whitespace-nowrap text-center">TRẠNG THÁI</th>      
                        <th class="whitespace-nowrap text-center">HÀNH ĐỘNG</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($danghoc as $item)
                    <tr class="intro-x">
                        <td class="text-center"><p class="font-medium">{{ $item->user->full_name }}</p></td>                     
                        <td class="text-center"><p class="font-medium">MGV: {{ $item->phancong->giangvien->mgv }}, Môn học: {{ $item->phancong->hocphan->title }}</p></td>
                        <td class="text-center"><p class="font-medium">{{ $item->noidung_id }}</p></td>   
                        <td class="text-center"><p class="font-medium">{{ $item->time_spending }}</p></td>                     
                        <td class="text-center"><p class="font-medium">{{ $item->status }}</p></td>                     
                        <td>
                            <div class="flex justify-center items-center space-x-2">
                                <a href="{{route('admin.learning.edit',$item->id)}}" class="flex items-center text-primary">
                                    <i data-lucide="edit" class="w-4 h-4 mr-1"></i> Edit
                                </a>
                                <form action="{{route('admin.learning.destroy',$item->id)}}" method="post" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <a href="javascript:;" class="flex items-center text-danger dltBtn" data-id="{{$item->id}}" data-tw-toggle="modal" data-tw-target="#delete-confirmation-modal">
                                        <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Delete
                                    </a>
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