<style>
    .title-overflow {
        max-width: 150px;
        min-width: 100px;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }
</style>

<div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
    <table class="table table-report -mt-2">
        <thead>
            <tr>
                <th class="whitespace-nowrap">TẬP TIN</th>
                <th class="whitespace-nowrap">TẢI LÊN TỪ</th>
                <th class="whitespace-nowrap">URL</th>
                <th class="text-center whitespace-nowrap">NGÀY GIỜ TẢI LÊN</th>
                <th class="text-center whitespace-nowrap">HÀNH ĐỘNG</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($resources as $resource)
                <tr class="intro-x">
                    <td>
                        <a href="{{ route('admin.resources.show', $resource->id) }}"
                            style="display: inline-flex; align-items: center;">
                            {{-- Kiểm tra loại file để hiển thị nội dung phù hợp --}}
                            @if ($resource->link_code)
                                @if ($resource->type_code == 'image')
                                    <img src="{{ $resource->url }}" alt="{{ $resource->title }}"
                                        style="width: 100px; height: 75px;" />
                                    <span class="title-overflow" style="margin-left: 8px;">{{ $resource->title }}</span>
                                @elseif ($resource->type_code == 'document')
                                    <span class="title-overflow" style="margin-left: 8px;">{{ $resource->title }} (Tải
                                        tài liệu)</span>
                                @elseif ($resource->type_code == 'video' && $resource->link_code == 'youtube')
                                    <iframe style="width: 100px; height: 75px;"
                                        src="{{ str_replace('watch?v=', 'embed/', $resource->url) }}" frameborder="0"
                                        allowfullscreen></iframe>
                                    <span class="title-overflow" style="margin-left: 8px;">{{ $resource->title }}</span>
                                @endif
                            @else
                                @php
                                    $fileType = $resource->file_type;
                                @endphp
                                @switch(true)
                                    @case(strpos($fileType, 'image/') === 0)
                                        <img src="{{ $resource->url }}" alt="{{ $resource->title }}"
                                            style="width: 100px; height: 75px;" />
                                        <span class="title-overflow" style="margin-left: 8px;">{{ $resource->title }}</span>
                                    @break

                                    @case(strpos($fileType, 'video/') === 0)
                                        <video controls style="width: 100px; height: 75px;">
                                            <source src="{{ $resource->url }}" type="{{ $fileType }}">
                                            Trình duyệt của bạn không hỗ trợ thẻ video.
                                        </video>
                                        <span class="title-overflow" style="margin-left: 8px;">{{ $resource->title }}</span>
                                    @break

                                    @case(strpos($fileType, 'audio/') === 0)
                                        <audio controls style="width: 100px; height: 75px;">
                                            <source src="{{ $resource->url }}" type="{{ $fileType }}">
                                            Trình duyệt của bạn không hỗ trợ thẻ audio.
                                        </audio>
                                        <span class="title-overflow" style="margin-left: 8px;">{{ $resource->title }}</span>
                                    @break

                                    @case($fileType === 'application/pdf')
                                        <embed src="{{ $resource->url }}" type="application/pdf"
                                            style="width: 100px; height: 75px;" />
                                        <span class="title-overflow" style="margin-left: 8px;">{{ $resource->title }}</span>
                                    @break

                                    @default
                                        <img src="{{ asset('backend/assets/icons/icon1.png') }}" alt="{{ $resource->title }}"
                                            style="width: 100px; height: 75px;" />
                                        <span class="title-overflow" style="margin-left: 8px;">{{ $resource->title }}</span>
                                @endswitch
                            @endif
                        </a>
                    </td>
                    <td>
                        {{ $resource->code }} <!-- Tải lên từ -->
                    </td>
                    <td>
                        <a href="{{ $resource->url }}" target="_blank">{{ $resource->url }}</a>
                    </td>

                    <td class="text-center">
                        {{ \Carbon\Carbon::parse($resource->created_at)->format('d/m/Y H:i') }}</td>

                    <td class="table-report__action w-56">
                        <div class="flex justify-center items-center">
                            <a class="flex items-center mr-3" href="{{ route('admin.resources.edit', $resource->id) }}"
                                title="Chỉnh sửa">
                                <i data-lucide="edit" class="w-4 h-4 mr-1"></i>
                            </a>
                            <form id="delete-form-{{ $resource->id }}"
                                action="{{ route('admin.resources.destroy', $resource->id) }}" method="POST"
                                style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <a class="dltBtn" data-id="{{ $resource->id }}" href="javascript:;" title="Xóa"
                                    style="color: #ef4444;">
                                    <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i>
                                </a>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="pagination" style="margin-top: 200px; margin-bottom: 20px;">
        {{ $resources->appends(request()->except('page'))->links() }}
    </div>
</div>
