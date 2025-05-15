<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Events\Models\EventType;
use Illuminate\Support\Str;

class  EventTypeController extends Controller
{
    public function index ()
    {
        try {
            $eventTypes = EventType::select('id', 'title', 'slug', 'location_type', 'created_at', 'updated_at')
        ->get();
        return response()->json([
            'success' => true,
            'data' => $eventTypes,
        ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy danh sách loại sự kiện: ' . $e->getMessage(),
            ], 500);
        }
    }
    public function getEventTypeById($id)
    {
        try {
            $eventType = EventType::find($id);
            if (!$eventType) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy loại sự kiện',
                ], 404);
            }
            return response()->json([
                'success' => true,
                'data' => $eventType,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy chi tiết loại sự kiện: ' . $e->getMessage(),
            ], 500);
        }
    }
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'title' => 'required|string|max:255',
                'location_type' => 'required|in:outdoor,indoor',
                'location_address'=>'nullable|string|max:255',
                'status' => 'required|in:active,inactive',
            ]);
            $validatedData['slug'] = Str::slug($data['title']);
            $eventType = EventType::create($validatedData);
            return response()->json(['success' => true, 'data' => $eventType], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi tạo loại sự kiện: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $eventType = EventType::find($id);
            if (!$eventType) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy loại sự kiện',
                ], 404);
            }
            $data = $request->validate([
                'title' => 'string|required',
                'location_type' => 'required|in:outdoor,indoor',
                'location_address'=>'nullable|string|max:255',
                'status' => 'required|in:active,inactive',
            ]);
            $validatedData['slug'] = Str::slug($data['title']);
            $status = $eventType->fill($validatedData)->save();
            if ($status) {
                return response()->json(['success' => true, 'data' => $eventType], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể cập nhật loại sự kiện',
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi cập nhật loại sự kiện: ' . $e->getMessage(),
            ], 500);
        }
    }
    public function destroy($id)
    {
        try {
            $eventType = EventType::find($id);
            if (!$eventType) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy loại sự kiện',
                ], 404);
            }
            $eventType->delete();
            return response()->json([
                'success' => true,
                'message' => 'Xóa loại sự kiện thành công',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi xóa loại sự kiện: ' . $e->getMessage(),
            ], 500);
        }
    }
}