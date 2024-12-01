@extends('backend.layouts.master')

@section('content')
    <h2 class="intro-y text-lg font-medium mt-10">Danh sách tài nguyên - Kết quả tìm kiếm</h2>

    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
        <a href="{{ route('admin.resources.create') }}" class="btn btn-primary shadow-md mr-2">Thêm tài nguyên</a>

        <div class="hidden md:block mx-auto text-slate-500">
            Hiển thị trang {{ $resources->currentPage() }} trong {{ $resources->lastPage() }} trang
        </div>
        <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
            <div class="w-56 relative text-slate-500">
                <form action="{{ route('admin.resources.search') }}" method="get">
                    <input type="hidden" name="view_mode" value="{{ $viewMode }}"> <!-- Giữ nguyên view mode -->
                    <input type="text" name="datasearch" class="ipsearch form-control w-56 box pr-10"
                        placeholder="Search..." value="{{ request()->get('datasearch') }}" autocomplete="off">
                    <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-lucide="search"></i>
                </form>
            </div>
        </div>
    </div>

    <!-- Switch between view modes -->
    <div class="intro-y flex items-center justify-between mt-2">
        <div class="flex-1">
            <select id="filterSelect" class="form-control w-56" onchange="filterResources()">
                <option value="">Lọc tài nguyên</option>
                <option value="">Tất cả</option>
                <optgroup label="Loại Tài Nguyên">
                    @foreach ($resourceTypes as $type)
                        <option value="type-{{ $type->type_code }}">{{ $type->type_code }}</option>
                    @endforeach
                </optgroup>
                <optgroup label="Loại Link">
                    @foreach ($linkTypes as $linkType)
                        @if ($linkType->link_code != null)
                            <option value="link-{{ $linkType->link_code }}">{{ $linkType->link_code }}</option>
                        @endif
                    @endforeach
                </optgroup>
                <optgroup label="Nơi Tải Lên">
                    @foreach ($uploaderSources as $source)
                        <option value="uploader-{{ $source->code }}">{{ $source->code }}</option>
                    @endforeach
                </optgroup>
            </select>
        </div>
        

        <div class="flex justify-end">
            <a href="{{ request()->fullUrlWithQuery(['view_mode' => 'pagination']) }}"
                class="btn {{ $viewMode === 'pagination' ? 'btn-primary' : '' }}" title="Phân trang">
                <i data-lucide="grid" class="w-4 h-4"></i>
            </a>
            <a href="{{ request()->fullUrlWithQuery(['view_mode' => 'ajax']) }}"
                class="btn {{ $viewMode === 'ajax' ? 'btn-primary' : '' }}" title="Tải AJAX">
                <i data-lucide="table" class="w-4 h-4"></i>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-6 mt-5" id="resource-list">
        @if ($resources->count() > 0)
            @if ($viewMode === 'pagination')
                @include('Resource::partials.pagination', ['resources' => $resources])
            @else
                @include('Resource::partials.ajax', ['resources' => $resources])
            @endif
        @else
            <div class="intro-y col-span-12">
                <div class="text-center text-slate-500">Không tìm thấy tài nguyên nào phù hợp với từ khóa
                    "<strong>{{ request()->get('datasearch') }}</strong>".</div>
            </div>
        @endif
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $('#filterSelect').on('change', function() {
            let selectedValue = $(this).val();
            if (!selectedValue) {
                window.location.href = "{{ route('admin.resources.index') }}";
                return;
            }
    
            let [type, code] = selectedValue.split('-');
            let params = {
                view_mode: '{{ $viewMode }}',
                datasearch: '{{ request()->get('datasearch') }}' // Preserve the search term
            };
            params[type] = code;
    
            // Construct the URL with parameters
            window.location.href = "{{ route('admin.resources.index') }}" + '?' + $.param(params);
        });
    
        $('.dltBtn').click(function(e) {
            e.preventDefault();
            const resourceId = $(this).data('id');
            const form = $('#delete-form-' + resourceId + ' form');
    
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
                    form.submit();
                    Swal.fire('Đã xóa!', 'Tài nguyên của bạn đã được xóa.', 'success');
                }
            });
        });
    </script>
    
@endsection
