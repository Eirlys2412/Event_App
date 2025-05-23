<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Events\Models\Event;
use App\Modules\Events\Models\EventTicket;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use App\Modules\Resource\Models\Resource;
use App\Modules\VNPay\Models\VNPayTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use App\Modules\TuongTac\Models\Vote;

class EventController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    // Lấy danh sách sự kiện
    public function index()
    {
        try {
            $events = Event::select(
                'id', 'title', 'slug', 'summary', 'description', 'resources', 
                'timestart', 'timeend', 'event_type_id', 'tags', 
                'created_at', 'updated_at'
            )->get();

            $events = $events->map(function ($event) {
                $resourceIds = json_decode($event->resources, true)['resource_ids'] ?? [];
                $resources = Resource::whereIn('id', $resourceIds)->get();

                $event->resources_data = $resources->map(function ($res) {
                    return [
                        'id' => $res->id,
                        'title' => $res->title,
                        'type' => $res->file_type,
                        'url' => URL::to($res->url), // URL đầy đủ (dùng cho frontend load ảnh/video)
                        'user_id' => $res->user_id,
                    ];
                });

                return $event;
            });

            return response()->json([
                'success' => true,
                'data' => $events,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy danh sách sự kiện: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function getEventById($id)
    {
        try {
            $event = Event::find($id);

            if (!$event) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy sự kiện',
                ], 404);
            }

            // Parse resource_ids
            $resourceIds = json_decode($event->resources, true)['resource_ids'] ?? [];

            // Lấy danh sách tài nguyên liên quan
            $resources = Resource::whereIn('id', $resourceIds)->get();

            // Gắn dữ liệu tài nguyên vào
            $event->resources_data = $resources->map(function ($res) {
                return [
                    'id'    => $res->id,
                    'title' => $res->title,
                    'type'  => $res->file_type,
                    'url'   => URL::to($res->url),
                    'user_id' => $res->user_id,
                ];
            });

            // Thêm thông tin đánh giá
            $event->rating_info = [
                'average_rating' => round(Vote::where([
                    'votable_id' => $id,
                    'votable_type' => 'App\Modules\Events\Models\Event'
                ])->avg('rating'), 2),
                'total_votes' => Vote::where([
                    'votable_id' => $id,
                    'votable_type' => 'App\Modules\Events\Models\Event'
                ])->count(),
                'rating_distribution' => [
                    '5_stars' => Vote::where([
                        'votable_id' => $id,
                        'votable_type' => 'App\Modules\Events\Models\Event',
                        'rating' => 5
                    ])->count(),
                    '4_stars' => Vote::where([
                        'votable_id' => $id,
                        'votable_type' => 'App\Modules\Events\Models\Event',
                        'rating' => 4
                    ])->count(),
                    '3_stars' => Vote::where([
                        'votable_id' => $id,
                        'votable_type' => 'App\Modules\Events\Models\Event',
                        'rating' => 3
                    ])->count(),
                    '2_stars' => Vote::where([
                        'votable_id' => $id,
                        'votable_type' => 'App\Modules\Events\Models\Event',
                        'rating' => 2
                    ])->count(),
                    '1_star' => Vote::where([
                        'votable_id' => $id,
                        'votable_type' => 'App\Modules\Events\Models\Event',
                        'rating' => 1
                    ])->count(),
                ]
            ];

            // Thêm đánh giá của user hiện tại nếu đã đăng nhập
            if (Auth::check()) {
                $userVote = Vote::where([
                    'user_id' => Auth::id(),
                    'votable_id' => $id,
                    'votable_type' => 'App\Modules\Events\Models\Event'
                ])->first();

                $event->user_rating = $userVote ? $userVote->rating : null;
            }

            return response()->json([
                'success' => true,
                'data' => $event,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy chi tiết sự kiện: ' . $e->getMessage(),
            ], 500);
        }
    }

    // Tạo sự kiện mới
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'title' => 'sometimes|string|max:255|unique:event,title',
                'summary' => 'nullable|string',
                'description' => 'nullable|string',
                'resources' => 'nullable|json',
                'timestart' => 'sometimes|date',
                'timeend' => 'sometimes|date',
                'event_type_id' => 'sometimes|exists:event_type,id',
                'tags' => 'nullable|json',
            ]);

            $validatedData['slug'] = Str::slug($validatedData['title']);

            $event = Event::create($validatedData);

            return response()->json(['success' => true, 'data' => $event], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // Cập nhật sự kiện
    public function update(Request $request, $id)
    {
        try {
            $event = Event::find($id);
            if (!$event) {
                return response()->json(['success' => false, 'message' => 'Không tìm thấy sự kiện'], 404);
            }

            $validatedData = $request->validate([
                'title' => 'sometimes|string|max:255',
                'summary' => 'nullable|string',
                'description' => 'nullable|string',
                'resources' => 'nullable|json',
                'timestart' => 'sometimes|date',
                'timeend' => 'sometimes|date|after_or_equal:timestart',
                'event_type_id' => 'sometimes|exists:event_type,id',
                'tags' => 'nullable|json',
            ]);

            if (isset($validatedData['title'])) {
                $validatedData['slug'] = Str::slug($validatedData['title']);
            }

            $event->update($validatedData);

            return response()->json(['success' => true, 'data' => $event], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // Xóa sự kiện
    public function destroy($id)
    {
        try {
            $event = Event::find($id);
            if (!$event) {
                return response()->json(['success' => false, 'message' => 'Không tìm thấy sự kiện'], 404);
            }
            $event->delete();
            return response()->json(['success' => true, 'message' => 'Xóa sự kiện thành công'], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // Thêm phương thức mới để thanh toán vé
    public function createEventPayment(Request $request)
    {
        $request->validate([
            'event_id' => 'required|integer|exists:events,id',
            'quantity' => 'required|integer|min:1',
            'ticket_type' => 'required|string|in:regular,vip'
        ]);

        $event = Event::findOrFail($request->event_id);
        
        // Kiểm tra số lượng vé còn lại
        if ($event->available_tickets < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Số lượng vé không đủ'
            ], 400);
        }

        // Tính tổng tiền (giá vé đã nhân với 100)
        $amount = $event->ticket_price * $request->quantity; // ticket_price là integer

        // Tạo mã đơn hàng unique
        $orderCode = 'EV' . time() . Str::random(6);

        // Tạo giao dịch VNPay
        $vnp_TxnRef = $orderCode;
        $vnp_OrderInfo = "Thanh toan ve su kien: " . $event->title;
        $vnp_OrderType = 'event_ticket';
        $vnp_Amount = $amount * 100; // Nhân 100 vì VNPay yêu cầu
        $vnp_Locale = 'vn';
        $vnp_IpAddr = request()->ip();

        // Tạo dữ liệu gửi đến VNPay
        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => env('VNPAY_TMN_CODE'),
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => route('vnpay.return'),
            "vnp_TxnRef" => $vnp_TxnRef,
        );

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        // Tạo URL thanh toán VNPay
        $vnp_Url = env('VNPAY_URL');
        $vnpSecureHash = hash_hmac('sha512', $hashdata, env('VNPAY_HASH_SECRET'));
        $vnp_Url .= "?" . $query;
        $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;

        // Lưu thông tin giao dịch vào database
        $transaction = VNPayTransaction::create([
            'order_id' => $vnp_TxnRef,
            'amount' => $amount,
            'order_info' => $vnp_OrderInfo,
            'status' => 'pending'
        ]);

        // Lưu vé vào bảng event_tickets
        EventTicket::create([
            'event_id' => $event->id,
            'user_id' => auth()->id(),
            'quantity' => $request->quantity,
            'ticket_type' => $request->ticket_type,
            'transaction_id' => $transaction->id,
            'total_amount' => $amount, // Lưu tổng số tiền đã thanh toán
            'status' => 'pending'
        ]);

        return redirect($vnp_Url);
    }

    // Xử lý sau khi thanh toán thành công
    public function processEventPayment($orderId)
    {
        try {
            $transaction = VNPayTransaction::where('order_id', $orderId)
                ->where('status', 'completed')
                ->firstOrFail();

            // Kiểm tra xem vé đã được tạo chưa
            if (EventTicket::where('transaction_id', $transaction->id)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vé đã được tạo cho giao dịch này'
                ], 400);
            }

            $orderInfo = json_decode($transaction->order_info, true);
            
            $ticket = EventTicket::create([
                'event_id' => $orderInfo['event_id'],
                'user_id' => $orderInfo['user_id'],
                'quantity' => $orderInfo['quantity'],
                'ticket_type' => $orderInfo['ticket_type'],
                'transaction_id' => $transaction->id,
                'status' => 'active'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Thanh toán thành công',
                'ticket' => $ticket->load(['event', 'user'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xử lý thanh toán'
            ], 500);
        }
    }

    public function uploadEventImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB
            'title' => 'nullable|string|max:255'
        ]);

        $file = $request->file('image');
        $filename = time() . '_' . $file->getClientOriginalName();

        // Resize và nén ảnh
        $img = Image::make($file->getRealPath())
            ->resize(1024, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })
            ->encode('webp', 80);

        $path = 'uploads/resources/' . $filename . '.webp';
        Storage::disk('public')->put($path, $img);

        // Lưu vào bảng resource
        $resource = Resource::create([
            'title' => $request->title ?? $filename,
            'file_type' => 'image/webp',
            'url' => 'storage/' . $path,
        ]);

        return response()->json([
            'success' => true,
            'resource' => [
                'id' => $resource->id,
                'title' => $resource->title,
                'url' => URL::to($resource->url),
            ]
        ]);
    }

    public function rateEvent(Request $request, $eventId)
    {
        try {
            $request->validate([
                'rating' => 'required|integer|min:1|max:5'
            ]);

            $event = Event::findOrFail($eventId);
            $userId = Auth::id();

            // Tìm đánh giá hiện tại của user
            $existingVote = Vote::where([
                'user_id' => $userId,
                'votable_id' => $eventId,
                'votable_type' => 'App\Modules\Events\Models\Event'
            ])->first();

            if ($existingVote) {
                // Nếu đã đánh giá thì cập nhật điểm
                $existingVote->update([
                    'rating' => $request->rating
                ]);
                $message = 'Cập nhật đánh giá thành công';
            } else {
                // Nếu chưa đánh giá thì tạo mới
                Vote::create([
                    'user_id' => $userId,
                    'votable_id' => $eventId,
                    'votable_type' => 'App\Modules\Events\Models\Event',
                    'rating' => $request->rating
                ]);
                $message = 'Đánh giá thành công';
            }

            // Tính điểm trung bình
            $averageRating = Vote::where([
                'votable_id' => $eventId,
                'votable_type' => 'App\Modules\Events\Models\Event'
            ])->avg('rating');

            // Đếm số lượt đánh giá
            $totalVotes = Vote::where([
                'votable_id' => $eventId,
                'votable_type' => 'App\Modules\Events\Models\Event'
            ])->count();

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => [
                    'rating' => $request->rating,
                    'average_rating' => round($averageRating, 2),
                    'total_votes' => $totalVotes
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi đánh giá: ' . $e->getMessage()
            ], 500);
        }
    }
}


