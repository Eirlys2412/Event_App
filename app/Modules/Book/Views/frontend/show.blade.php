@extends('frontend.layouts.master')
@section('head_css')

@section('content')

<div class="container mx-auto mt-8">
    <div class="flex flex-wrap">
        <!-- Phần bên trái: Ảnh bìa -->
        <div class="w-full lg:w-4/12 xl:w-5/12 p-1">
            <div class="intro-y box mt-3 p-0"> <!-- Giảm padding -->
                <div class="relative flex items-center p-0"> <!-- Giảm padding -->
                    <div class="mx-6">
                        <div class="single-item">
                            <?php
                            $photos = explode(',', $book->photo);
                            ?>
                            @foreach ($photos as $photo)
                                <div class="h-64 px-2 mb-4">
                                    <div class="h-full bg-slate-100 dark:bg-darkmode-400 rounded-md">
                                        <img src="{{ $photo }}" class="w-full h-64 object-cover rounded-md mx-auto" />
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Phần bên phải: Thông tin sách -->
        <div class="w-full lg:w-8/12 xl:w-7/12 p-1">
            <div class="intro-y box p-0">
                <div class="mx-6">
                    <h2 class="font-medium text-base mr-auto">{{ $book->title }}</h2>
                    <div class="grid grid-cols-12 gap-x-5">
                        <div class="col-span-12 sm:col-span-6">
                            <label class="font-medium form-label">Tác giả:</label>
                            <p>{{ $book->user->full_name }}</p>
                        </div>
                        <div class="col-span-12 sm:col-span-6">
                            <label class="font-medium form-label">Loại sách:</label>
                            <p>{{ $book->bookType->title }}</p>
                        </div>
                        <div class="col-span-12 sm:col-span-6">
                            <label class="font-medium form-label">Tag:</label>
                            <p>
                                @foreach ($tagNames as $tagName)
                                    {{ $tagName }}@if (!$loop->last)
                                        ,
                                    @endif
                                @endforeach
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tóm tắt và Nội dung, chiếm 12 cột -->
    <div class="intro-y box p-1"> <!-- Giảm padding -->
        <div class="p-0"> <!-- Giảm padding -->
            <div class="col-span-12 mt-1">
                <label class="font-medium form-label">Tóm tắt:</label>
                <p>{!! $book->summary !!}</p>
            </div>
            <div class="col-span-12 mt-1">
                <label class="font-medium form-label">Nội dung:</label>
                <p>{!! $book->content !!}</p>
            </div>
        </div>
        <div class="relative flex items-center p-0"> <!-- Giảm padding -->
            <div class="p-3">
                @if ($resources->count() > 0)
                    @foreach ($resources as $resource)
                        @if ($resource->file_type == 'video/mp4')
                            <div class="my-2">
                                
                                <video controls class="w-full h-auto mx-auto rounded-md">
                                    <source src="{{ asset($resource->url) }}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                        @elseif (in_array($resource->file_type, ['image/jpeg', 'image/png', 'image/gif']))
                            <div class="my-2">
                                
                                <img src="{{ asset($resource->url) }}" alt="Resource Image"
                                     class="w-full h-32 object-cover rounded-md mx-auto">
                            </div>
                        @elseif ($resource->file_type == 'audio/mp3')
                            <div class="my-2">
                                
                                <audio controls class="w-full h-24 mx-auto rounded-md">
                                    <source src="{{ asset($resource->url) }}" type="audio/mp3">
                                    Your browser does not support the audio tag.
                                </audio>
                            </div>
                        @else
                            <div class="my-2">
                                <p>Không hỗ trợ định dạng tệp này.</p>
                            </div>
                        @endif
                    @endforeach
                @else
                    <p>Không có tài nguyên nào.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Tài nguyên (ảnh, video, âm thanh), chiếm 12 cột -->
    <!-- Giảm padding -->
        
    </div>

</div>

@endsection

@section('scripts')
<script>
    function togglePiP(videoId) {
        const videoElement = document.getElementById(videoId);

        // Kiểm tra xem trình duyệt có hỗ trợ Picture-in-Picture không
        if (document.pictureInPictureEnabled) {
            if (videoElement !== document.pictureInPictureElement) {
                // Kích hoạt Picture-in-Picture
                videoElement.requestPictureInPicture()
                    .catch(error => {
                        console.log("Error entering Picture-in-Picture: ", error);
                    });
            } else {
                // Thoát khỏi Picture-in-Picture
                document.exitPictureInPicture()
                    .catch(error => {
                        console.log("Error exiting Picture-in-Picture: ", error);
                    });
            }
        } else {
            alert("Your browser does not support Picture-in-Picture.");
        }
    }
</script>
@endsection
