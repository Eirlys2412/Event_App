@extends('backend.layouts.master')
@section('content')

 
 
    <h2 class="intro-y text-lg font-medium mt-10">
        Danh sách giảng viên
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            <a href="{{route('admin.teacher.create')}}" class="btn btn-primary shadow-md mr-2">Thêm giảng viên</a>
            
            <div class="hidden md:block mx-auto text-slate-500">Hiển thị trang {{$teachers->currentPage()}} trong {{$teachers->lastPage()}} trang</div>
            <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                <div class="w-56 relative text-slate-500">
                    <form action="{{ route('admin.teacher.search') }}" method="get">
                        <input type="text" 
                               name="datasearch" 
                               class="ipsearch form-control w-56 box pr-10" 
                               placeholder="Search..."
                               value="{{ isset($search) ? $search : '' }}">
                        <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-lucide="search"></i> 
                    </form>
                </div>
            </div>
        </div>
        <!-- BEGIN: Data List -->
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
    <table class="table table-report -mt-2">
        <thead>
            <tr>
                <th class="whitespace-nowrap">MGV</th>
                <th class="whitespace-nowrap">TÊN</th>
                <th class="whitespace-nowrap">ĐƠN VỊ</th>
                <th class="text-center whitespace-nowrap">CHUYÊN NGÀNH</th>
                {{-- <th class="text-center whitespace-nowrap">TRẠNG THÁI</th> --}}
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($teachers as $item)
            <tr class="intro-x">
                <td>
                    <a target="_blank" href="" class="font-medium whitespace-nowrap">{{ $item->mgv }}</a> 
                </td>
                <td class="text-left">{{ $item->user->full_name ?? 'N/A' }}</td> <!-- Lấy tên người dùng -->
                <td class="text-left">{{ $item->donVi->title ?? 'N/A' }}</td> <!-- Lấy tên đơn vị -->
                <td class="text-left">{{ $item->chuyenNganhs->title ?? 'N/A' }}</td> <!-- Lấy tên chuyên ngành -->
                <td class="table-report__action w-56">
                    <div class="flex justify-center items-center">
                        <a href="{{ route('admin.teacher.edit', $item->id) }}" class="flex items-center mr-3"> 
                            <i data-lucide="check-square" class="w-4 h-4 mr-1"></i> Edit 
                        </a>
                        <form action="{{ route('admin.teacher.destroy', $item->id) }}" method="post">
                            @csrf
                            @method('delete')
                            <a class="flex items-center text-danger dltBtn" data-id="{{ $item->id }}" href="javascript:;" data-tw-toggle="modal" data-tw-target="#delete-confirmation-modal"> 
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
        <!-- BEGIN: Pagination -->
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
            <nav class="w-full sm:w-auto sm:mr-auto">
                {{ $teachers->links('vendor.pagination.tailwind') }}
            </nav>
        </div>
<!-- END: Pagination -->

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