<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
// use Intervention\Image\Facades\Image; // Xóa dòng này
use App\Modules\Resource\Models\Resource; // Sử dụng model Resource từ Module
use App\Modules\Events\Models\Event;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str; // Import Str facade nếu cần cho pathinfo
use Illuminate\Support\Facades\Log; // Thêm dòng này để import facade Log

class EventImageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Tải ảnh lên cho một sự kiện cụ thể và lưu thông tin người gửi.
     * Lưu file gốc không resize/nén.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $eventId
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadEventImage(Request $request, $eventId)
    {
        try {
            // Kiểm tra sự kiện tồn tại
            $event = Event::findOrFail($eventId);

            // Xác thực request
            $request->validate([
                'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240', // 10MB max, định dạng cho phép
                'titles.*' => 'nullable|string|max:255' // Tiêu đề cho mỗi ảnh (tùy chọn)
            ]);

            DB::beginTransaction();

            $uploadedResources = [];
            // Lấy resource_ids hiện có hoặc khởi tạo mảng rỗng
            $resourceIds = json_decode($event->resources, true)['resource_ids'] ?? [];

            // Lấy ID của người dùng đang đăng nhập
            /** @var \Illuminate\Contracts\Auth\Guard $authGuard */
            $authGuard = auth();
            /** @var \App\Models\User|null $user */
            $user = $authGuard->user();
            $uploaderUserId = $user ? $user->id : null;
            if (!$uploaderUserId) {
                 throw new \Exception("Người dùng chưa đăng nhập."); // Hoặc trả về lỗi 401
            }


            // Xử lý từng ảnh được gửi lên
            foreach ($request->file('images') as $index => $file) {
                // Tạo một request tạm thời chỉ chứa file và tiêu đề tương ứng cho Resource::createResource
                $resourceRequest = new Request();
                $resourceRequest->setMethod('POST');
                $resourceRequest->files->set('file', $file); // Đặt file vào key 'file' như createResource mong đợi

                // Thêm tiêu đề nếu có
                if (isset($request->titles[$index])) {
                    $resourceRequest->request->set('title', $request->titles[$index]);
                }

                // Sử dụng phương thức createResource từ model Resource
                $resource = Resource::createResource(
                    $resourceRequest, // Truyền request tạm thời chứa file và title
                    $file, // Truyền đối tượng file
                    'Event' // Truyền mã module là 'Event'
                );

                // Gán user_id sau khi tạo resource, nếu cần
                $resource->user_id = $uploaderUserId;
                $resource->save();

                // Thêm ID resource mới vào mảng
                $resourceIds[] = $resource->id;

                // Chuẩn bị dữ liệu trả về cho frontend
                $uploadedResources[] = [
                    'id' => $resource->id,
                    'title' => $resource->title,
                    'file_type' => $resource->file_type,
                    'url' => URL::to($resource->url), // Tạo URL đầy đủ để frontend hiển thị
                    'uploaded_by_user_id' => $resource->user_id,
                    // Có thể thêm tên người dùng nếu eager load relationship 'user' tại đây
                ];
            }

            // Cập nhật trường 'resources' của sự kiện với danh sách ID mới
            $event->resources = json_encode([
                'event_id' => $event->id,
                'resource_ids' => array_unique($resourceIds) // Đảm bảo không có ID trùng lặp
            ]);
            $event->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Upload ảnh thành công.',
                'data' => [
                    'event_id' => $event->id,
                    'resources' => $uploadedResources // Trả về thông tin các resource đã upload
                ]
            ], 201); // Trả về status code 201 Created

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
             DB::rollBack();
             return response()->json([
                 'success' => false,
                 'message' => 'Không tìm thấy sự kiện.'
             ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
             DB::rollBack();
             return response()->json([
                 'success' => false,
                 'message' => 'Lỗi xác thực dữ liệu tải lên.',
                 'errors' => $e->errors()
             ], 422); // Unprocessable Entity
        } catch (\Exception $e) {
            DB::rollBack();
            // Ghi log lỗi chi tiết trên server
            Log::error("Upload ảnh sự kiện lỗi: " . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi khi upload ảnh: ' . $e->getMessage() // Trả về lỗi chi tiết trong môi trường dev/staging
                // message' => 'Đã xảy ra lỗi khi upload ảnh.' // Thông báo chung trong môi trường production
            ], 500);
        }
    }

    public function deleteEventImage($eventId, $resourceId)
    {
        try {
            DB::beginTransaction();

            // Kiểm tra sự kiện tồn tại
            $event = Event::findOrFail($eventId);

            // Kiểm tra resource tồn tại
            $resource = Resource::findOrFail($resourceId);

            // Xóa file từ storage
            if (Storage::disk('public')->exists(str_replace('storage/', '', $resource->url))) {
                Storage::disk('public')->delete(str_replace('storage/', '', $resource->url));
            }

            // Xóa record từ database
            $resource->delete();

            // Cập nhật resources của event
            $resourceIds = json_decode($event->resources, true)['resource_ids'] ?? [];
            $resourceIds = array_filter($resourceIds, fn($id) => $id != $resourceId);

            $event->resources = json_encode([
                'event_id' => $event->id,
                'resource_ids' => array_values($resourceIds)
            ]);
            $event->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Xóa ảnh thành công'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xóa ảnh: ' . $e->getMessage()
            ], 500);
        }
    }

    // Có thể xóa phương thức store này nếu không dùng đến
    // public function store(Request $request, Event $event)
    // {
    //     $request->validate([
    //         'image' => 'required|image|max:2048', // Validate it's an image and max 2MB
    //     ]);

    //     $path = $request->file('image')->store('public/events/' . $event->id . '/images');

    //     // You might want to save the image path to the database here
    //     // For now, just return the path
    //     return response()->json(['path' => Storage::url($path)], 201);
    // }
} 