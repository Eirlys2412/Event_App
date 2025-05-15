@extends('backend.layouts.master')
@section('content')

 
 
    <h2 class="intro-y text-lg font-medium mt-10">
        Danh sách chi tiết chương trình
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            <a href="{{route('admin.program_details.create')}}" class="btn btn-primary shadow-md mr-2">Thêm chi tiết chương trình</a>

            <div class="hidden md:block mx-auto text-slate-500">Hiển thị trang {{$program_details->currentPage()}} trong {{$program_details->lastPage()}} trang</div>
            
            <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                <div class="w-56 relative text-slate-500">
                    <form action="{{route('admin.program_details.search')}}" method = "get">
                        @csrf
                        <input type="text" name="datasearch" class="ipsearch form-control w-56 box pr-10" placeholder="Search...">
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
                <th class="whitespace-nowrap">Học phần</th>
                <th class="whitespace-nowrap">Chương trình đào tạo</th>
                <th class="whitespace-nowrap">Loại học phần</th>
                <th class="whitespace-nowrap">Học kỳ</th>
                <th class="whitespace-nowrap">Học phần tiên quyết</th>
                <th class="whitespace-nowrap">Học phần song song</th>
                {{-- <th class="text-center whitespace-nowrap">CHUYÊN NGÀNH</th> --}}
                <th class="text-center whitespace-nowrap">TRẠNG THÁI</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($program_details as $item)
<tr class="intro-x">
    <td>
        <a target="_blank" href="" class="font-medium whitespace-nowrap">{{ $item->hocPhan->title ?? 'N/A' }}</a>
    </td>
    <td class="text-left">{{ $item->chuongTrinhdaotao->title ?? 'N/A' }}</td>
    <td class="text-left">{{ $item->loai ?? 'N/A' }}</td>
    <td class="text-left">{{ $item->hocKy->so_hoc_ky ?? 'N/A' }}</td>

    <!-- Hiển thị học phần tiên quyết -->
    <td class="text-left">
        @php
            $hocphantienquyet = json_decode($item->hocphantienquyet, true);
        @endphp
        @if($hocphantienquyet && isset($hocphantienquyet['next']))
            {{ implode(', ', array_map(function($id) use ($hocPhanList) {
                return $hocPhanList[$id] ?? 'N/A';
            }, $hocphantienquyet['next'])) }}
        @else
            N/A
        @endif
    </td>

    <!-- Hiển thị học phần song song -->
    <td class="text-left">
        @php
            $hocphansongsong = json_decode($item->hocphansongsong, true);
        @endphp
        @if($hocphansongsong && isset($hocphansongsong['parallel']))
            {{ implode(', ', array_map(function($id) use ($hocPhanList) {
                return $hocPhanList[$id] ?? 'N/A';
            }, $hocphansongsong['parallel'])) }}
        @else
            N/A
        @endif
    </td>

    <!-- Hành động -->
    <td class="table-report__action w-56">
        <div class="flex justify-center items-center">
            <a href="{{ route('admin.program_details.edit', $item->id) }}" class="flex items-center mr-3"> 
                <i data-lucide="check-square" class="w-4 h-4 mr-1"></i> Edit 
            </a>
            <form action="{{ route('admin.program_details.destroy', $item->id) }}" method="post">
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
                {{ $program_details->links('vendor.pagination.tailwind') }}
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
        console.log("Delete button clicked");
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