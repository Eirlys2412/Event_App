@extends('backend.layouts.master')

@section('scriptop')

@section('content')

    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Thông tin sách
        </h2>
    </div>

    <div class="grid grid-cols-12 gap-6">
        <!-- BEGIN: Profile Menu (Bên trái, ảnh bìa) -->
        <div class="col-span-12 lg:col-span-4 2xl:col-span-3 flex lg:block flex-col-reverse">
            <?php
            $photos = explode(',', $book->photo);
            ?>
            <div class="intro-y box mt-5">
                <div class="relative flex items-center p-5">
                    <div class="mx-6">
                        <label class="font-medium">Ảnh bìa:</label>
                        <div class="single-item">
                            @foreach ($photos as $photo)
                                <div class="h-24 px-2 mb-4">
                                    <div class="h-full bg-slate-100 dark:bg-darkmode-400 rounded-md">
                                        <img src="{{ $photo }}"
                                            class="w-full h-24 object-cover rounded-md mx-auto" />
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="intro-y box mt-5">
                <div class="relative flex items-center p-5">
                    <div class="p-6">
                        @if ($resources->count() > 0)
                            @foreach ($resources as $resource)
                                @if ($resource->file_type == 'video/mp4')
                                    <div class="my-2">
                                        <label class="font-medium">Video:</label>
                                        <video controls class="w-full h-auto mx-auto rounded-md">
                                            <source src="{{ asset($resource->url) }}" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                    </div>
                                @elseif (in_array($resource->file_type, ['image/jpeg', 'image/png', 'image/gif']))
                                    <div class="my-2">
                                        <label class="font-medium">Ảnh:</label>
                                        <img src="{{ asset($resource->url) }}" alt="Resource Image"
                                            class="w-full h-32 object-cover rounded-md mx-auto">
                                    </div>
                                @elseif ($resource->file_type == 'audio/mp3')
                                    <div class="my-2">
                                        <label class="font-medium">Âm thanh:</label>
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
        </div>
        <!-- END: Resources Section -->

        <!-- BEGIN: Display Information (Bên phải, thông tin sách) -->
        <div class="intro-y box lg:mt-5 col-span-12 lg:col-span-8 2xl:col-span-9">
            <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                <h2 class="font-medium text-base mr-auto">
                    {{ $book->title }}
                </h2>
            </div>

            <div class="p-5">
                <div class="flex flex-col xl:flex-row gap-6">
                    <div class="flex-1">
                        <div class="grid grid-cols-12 gap-x-5">
                            <div class="col-span-12 sm:col-span-6">
                                <label for="update-profile-form-1" class="font-medium form-label">Tác giả:</label>
                                <p>{{ $book->user->full_name }}</p>
                            </div>

                            <div class="col-span-12 sm:col-span-6">
                                <label for="update-profile-form-1" class="font-medium form-label">Loại sách:</label>
                                <p>{{ $book->bookType->title }}</p>
                            </div>

                            <div class="col-span-12 sm:col-span-6">
                                <label for="update-profile-form-1" class="font-medium form-label">Trạng thái:</label>
                                <p>{{ $book->status }}</p>
                            </div>

                            <div class="col-span-12">
                                <label for="update-profile-form-1" class="font-medium form-label">Tóm tắt:</label>
                                <p>{!! $book->summary !!}</p>
                            </div>

                            <div class="col-span-12">
                                <label for="update-profile-form-1" class="font-medium form-label">Nội dung:</label>
                                <p>{!! $book->content !!}</p>
                            </div>

                            <div class="col-span-12">
                                <label for="update-profile-form-1" class="font-medium form-label">Tags:</label>
                                <p>
                                    @foreach ($tagNames as $tagName)
                                        {{ $tagName }}@if (!$loop->last)
                                            ,
                                        @endif
                                    @endforeach
                                </p>
                            </div>

                            <div class="col-span-12">
                                <label for="update-profile-form-1" class="font-medium form-label">Tài nguyên:</label>
                                <p>
                                    @foreach ($resources as $resource)
                                        <a href="{{ asset($resource->url) }}"
                                            target="_blank">{{ basename($resource->url) }}</a><br>
                                    @endforeach
                                </p>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END: Display Information -->
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
