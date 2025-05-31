<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use App\Http\Controllers\Api\EventImageController; // Sửa lại import controller
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\VoteController;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\StatisticController;
use App\Http\Controllers\Api\EventUserApiController; // Đảm bảo EventUserApiController được import đúng

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

// Api 
Route::group(['namespace' => 'api', 'prefix' => 'v1'], function () {

    // Authentication
    Route::post('login', [\App\Http\Controllers\Api\AuthenticationController::class, 'store']);
    Route::post('logout', [\App\Http\Controllers\Api\AuthenticationController::class, 'destroy'])->middleware('auth:api');
    Route::post('/register', [\App\Http\Controllers\Api\AuthenticationController::class, 'savenewUser']);
    Route::post('google-sign-in', [\App\Http\Controllers\Api\AuthenticationController::class, 'googleSignIn']);
    Route::post('/password/send-reset-code', [\App\Http\Controllers\Api\PasswordRecoveryController::class, 'sendResetCode']);
    Route::post('/password/reset', [\App\Http\Controllers\Api\PasswordRecoveryController::class, 'resetPassword']);
    
    //UniverInfo
    Route::get('/nganhs', [\App\Http\Controllers\Api\UniverInfoController::class, 'getNganhs']);
    Route::get('/donvi', [\App\Http\Controllers\Api\UniverInfoController::class, 'getDonVis']);
    Route::get('/chuyenNganh', [\App\Http\Controllers\Api\UniverInfoController::class, 'chuyenNganhs']);

     
    //Profile
    Route::post('updateprofile', [\App\Http\Controllers\Api\ApiUserController::class, 'updateProfile'])->middleware('auth:api');
    Route::get('profile', [\App\Http\Controllers\Api\ApiUserController::class, 'viewProfile'])->middleware('auth:api');
    Route::post('upload-photo', [\App\Http\Controllers\Api\ApiUserController::class, 'uploadPhoto'])->middleware('auth:api');
    Route::get('/users/{id}', [\App\Http\Controllers\Api\ApiUserController::class, 'show'])->middleware('auth:api');


    //Student
    // Route::get('/student/{userId}', [\App\Http\Controllers\Api\StudentController::class, 'show'])->middleware('auth:api');
    // Route::put('/student/{userId}', [\App\Http\Controllers\Api\StudentController::class, 'update'])->middleware('auth:api');
    // Route::post('student', [\App\Http\Controllers\Api\AuthenticationController::class, 'createStudent'])->middleware('auth:api');


    //Teacher
    // Route::get('/teacher/{userId}', [\App\Http\Controllers\Api\TeacherController::class, 'show'])->middleware('auth:api');
    // Route::put('/teacher/{userId}', [\App\Http\Controllers\Api\TeacherController::class, 'update'])->middleware('auth:api');
    // Route::post('teacher', [\App\Http\Controllers\Api\AuthenticationController::class, 'createTeacher'])->middleware('auth:api');
    
    //Phan cong
    Route::get('/phancong', [\App\Http\Controllers\Api\UniverInfoController::class, 'index']);

    //Course
    Route::get('/courses', [\App\Http\Controllers\Api\CourseController::class, 'getAvailableCourses']);// lấy danh sách khóa học có sẵn
    Route::post('/enroll', [\App\Http\Controllers\Api\CourseController::class, 'enrollCourse']);// đăng ký khóa học

    //event
    Route::get('/events', [\App\Http\Controllers\Api\EventController::class, 'index']);// lấy danh sách sự kiện
    Route::post('/event', [\App\Http\Controllers\Api\EventController::class, 'store']);// tạo sự kiện
    Route::put('/event/{id}', [\App\Http\Controllers\Api\EventController::class, 'update']);// cập nhật sự kiện
    Route::delete('/event/{id}', [\App\Http\Controllers\Api\EventController::class, 'destroy']);// xóa sự kiện
    Route::get('/event/{id}', [\App\Http\Controllers\Api\EventController::class, 'getEventById']);// lấy chi tiết sự kiện
    Route::post('/event/{id}/rate', [\App\Http\Controllers\Api\EventController::class, 'rateEvent']);// đánh giá sự kiện

    Route::get('/event-types', [\App\Http\Controllers\Api\EventTypeController::class, 'index']); // Lấy danh sách loại sự kiện
    Route::get('/event-types/{id}', [\App\Http\Controllers\Api\EventTypeController::class, 'getEventTypeById']); // Lấy chi tiết loại sự kiện
    Route::post('/event-types', [\App\Http\Controllers\Api\EventTypeController::class, 'store']); // Tạo loại sự kiện
    Route::put('/event-types/{id}', [\App\Http\Controllers\Api\EventTypeController::class, 'update']); // Cập nhật loại sự kiện
    Route::delete('/event-types/{id}', [\App\Http\Controllers\Api\EventTypeController::class, 'destroy']); // Xóa loại sự kiện
    
    Route::get('/event-users', [\App\Http\Controllers\Api\EventUserApiController::class, 'index']); // Danh sách người dùng tham gia sự kiện
    Route::post('/event-users', [\App\Http\Controllers\Api\EventUserApiController::class, 'store']); // Thêm mới người dùng vào sự kiện
    Route::get('/event-users/{id}', [\App\Http\Controllers\Api\EventUserApiController::class, 'show']); // Xem chi tiết 1 người dùng sự kiện
    Route::put('/event-users/{id}', [\App\Http\Controllers\Api\EventUserApiController::class, 'update']); // Cập nhật
    Route::delete('/event-users/{id}', [EventUserApiController::class, 'destroy']); // Xóa, sửa namespace
    Route::get('/event-users/user/{userId}', [\App\Http\Controllers\Api\EventUserApiController::class, 'getUserEvents']);


    Route::post('/join', [\App\Http\Controllers\Api\EventUserApiController::class, 'joinEvent']); // Người dùng tham gia sự kiện
    Route::get('/event/{eventId}/participants', [\App\Http\Controllers\Api\EventUserApiController::class, 'listParticipants']); // Lấy danh sách người tham gia sự kiện theo event_id
      
    Route::get('/event-attendance', [\App\Http\Controllers\Api\EventAttendanceApiController::class, 'index']);
    Route::post('/event-attendance', [\App\Http\Controllers\Api\EventAttendanceApiController::class, 'store']);
    Route::get('/event-attendance/{id}', [\App\Http\Controllers\Api\EventAttendanceApiController::class, 'show']);
    Route::put('/event-attendance/{id}', [\App\Http\Controllers\Api\EventAttendanceApiController::class, 'update']);
    Route::delete('/event-attendance/{id}', [\App\Http\Controllers\Api\EventAttendanceApiController::class, 'destroy']);
    Route::post('/check-in/{eventId}', [\App\Http\Controllers\Api\EventAttendanceApiController::class, 'checkInByQr'])->middleware('auth:api');
    Route::post('/event-attendance/check-in', [\App\Http\Controllers\Api\EventAttendanceApiController::class, 'checkInByQr'])->middleware('auth:api');
    Route::post('/event_registrations', [\App\Http\Controllers\Api\EventRegistrationApiController::class, 'register']);// đăng ký sự kiện
    Route::get('/event_registrations/my-registrations', [\App\Http\Controllers\Api\EventRegistrationApiController::class, 'myRegistrations']);// lấy danh sách sự kiện đã đăng ký của người dùng    
    Route::get('/event_registrations/{id}', [\App\Http\Controllers\Api\EventRegistrationApiController::class, 'show']);// xem chi tiết sự kiện đã đăng ký       
    Route::put('/event_registrations/{id}', [\App\Http\Controllers\Api\EventRegistrationApiController::class, 'update']);// cập nhật sự kiện đã đăng ký         
    Route::delete('/event_registrations/{id}', [\App\Http\Controllers\Api\EventRegistrationApiController::class, 'destroy']);// xóa sự kiện đã đăng ký  

    Route::get('/event-manager/pending-registrations', [\App\Http\Controllers\Api\EventManagerApiController::class, 'pendingRegistrations']);// lấy danh sách sự kiện đang chờ phê duyệt
    Route::post('/event-manager/registrations/{id}/approve', [\App\Http\Controllers\Api\EventManagerApiController::class, 'approveRegistration']);// phê duyệt sự kiện
    Route::post('/event-manager/registrations/{id}/reject', [\App\Http\Controllers\Api\EventManagerApiController::class, 'rejectRegistration']);// từ chối sự kiện
    Route::get('/event-manager/events/{eventId}/registrations', [\App\Http\Controllers\Api\EventManagerApiController::class, 'eventRegistrations']);// lấy danh sách sự kiện đã đăng ký của sự kiện 
    

    Route::post('events/payment/create', [\App\Http\Controllers\Api\EventController::class, 'createEventPayment']);// tạo đơn hàng thanh toán sự kiện
    Route::get('events/payment/process/{orderId}', [\App\Http\Controllers\Api\EventController::class, 'processEventPayment']);// xử lý thanh toán sự kiện
  
    Route::get('community/groups', [\App\Http\Controllers\Api\CommunityController::class, 'index']);// lấy danh sách nhóm
    Route::post('community/groups', [\App\Http\Controllers\Api\CommunityController::class, 'store'])->middleware('auth:api');// tạo nhóm            
    Route::get('community/groups/{id}', [\App\Http\Controllers\Api\CommunityController::class, 'show']);// lấy chi tiết nhóm
    Route::post('/groups/{groupId}/request-to-join', [\App\Http\Controllers\Api\CommunityController::class, 'requestToJoin'])->middleware('auth:api');// gửi yêu cầu tham gia nhóm
    Route::delete('/groups/{groupId}/cancel-join-request', [\App\Http\Controllers\Api\CommunityController::class, 'cancelJoinRequest'])->middleware('auth:api');// hủy yêu cầu tham gia nhóm    
    Route::get('/groups/{groupId}/join-requests', [\App\Http\Controllers\Api\CommunityController::class, 'getJoinRequests'])->middleware('auth:api');// lấy danh sách yêu cầu tham gia nhóm
    Route::post('/groups/{groupId}/join-requests/{requestId}/manage', [\App\Http\Controllers\Api\CommunityController::class, 'manageJoinRequest'])->middleware('auth:api'); // quản lý yêu cầu tham gia nhóm
    Route::get('/groups/join-requests/status', [\App\Http\Controllers\Api\CommunityController::class, 'getJoinRequestStatus'])->middleware('auth:api');// lấy trạng thái yêu cầu tham gia nhóm
    Route::post('/community/upload-cover', [\App\Http\Controllers\Api\CommunityController::class, 'uploadCover'])->middleware('auth:api');// tải ảnh bìa nhóm
  
    // Thêm các route cho CommentController
    Route::get('comments', [\App\Http\Controllers\Api\CommentController::class, 'index'])->name('comments.index');
    Route::post('comments', [\App\Http\Controllers\Api\CommentController::class, 'store'])->middleware('auth:api')->name('comments.store');
    Route::put('comments/{id}', [\App\Http\Controllers\Api\CommentController::class, 'update'])->middleware('auth:api')->name('comments.update');
    Route::delete('comments/{id}', [\App\Http\Controllers\Api\CommentController::class, 'destroy'])->middleware('auth:api')->name('comments.destroy');
    Route::post('/comments/{id}/reply', [\App\Http\Controllers\Api\CommentController::class, 'reply']); // 👉 Route trả lời bình luận
    Route::post('/comments/{id}/toggle-like', [CommentController::class, 'toggleLike']); // 👉 Route thích/bỏ thích bình luận
    // Community
    Route::get('community/groups', [\App\Http\Controllers\Api\CommunityController::class, 'index']);// lấy danh sách nhóm
    Route::post('community/groups', [\App\Http\Controllers\Api\CommunityController::class, 'store'])->middleware('auth:api');// tạo nhóm    
    Route::get('community/groups/{id}', [\App\Http\Controllers\Api\CommunityController::class, 'show']);// lấy chi tiết nhóm
    Route::post('/groups/{groupId}/request-to-join', [\App\Http\Controllers\Api\CommunityController::class, 'requestToJoin'])->middleware('auth:api');// gửi yêu cầu tham gia nhóm
    Route::delete('/groups/{groupId}/cancel-join-request', [\App\Http\Controllers\Api\CommunityController::class, 'cancelJoinRequest'])->middleware('auth:api');// hủy yêu cầu tham gia nhóm
    Route::get('/groups/{groupId}/join-requests', [\App\Http\Controllers\Api\CommunityController::class, 'getJoinRequests'])->middleware('auth:api');// lấy danh sách yêu cầu tham gia nhóm
    Route::post('/groups/{groupId}/join-requests/{requestId}/manage', [\App\Http\Controllers\Api\CommunityController::class, 'manageJoinRequest'])->middleware('auth:api');// quản lý yêu cầu tham gia nhóm
    Route::get('/groups/join-requests/status', [\App\Http\Controllers\Api\CommunityController::class, 'getJoinRequestStatus'])->middleware('auth:api');// lấy trạng thái yêu cầu tham gia nhóm
    Route::post('/community/upload-cover', [\App\Http\Controllers\Api\CommunityController::class, 'uploadCover'])->middleware('auth:api');// tải ảnh bìa nhóm
    
    // Blog
    
        // Danh sách tất cả bài viết
        Route::get('blogs', [\App\Http\Controllers\Api\BlogController::class, 'getAll']);
    
        // Lấy chi tiết 1 bài viết theo id hoặc slug
        Route::get('blog', [\App\Http\Controllers\Api\BlogController::class, 'getBlog']);
    
        // Lọc bài viết theo danh mục hoặc tag
        Route::get('blogs/filter', [\App\Http\Controllers\Api\BlogController::class, 'filter']);
    
        // Tìm kiếm bài viết theo từ khóa
        Route::get('blogs/search', [\App\Http\Controllers\Api\BlogController::class, 'search']);
    
        // Tạo mới bài viết (yêu cầu đăng nhập)
        Route::post('blog/store', [\App\Http\Controllers\Api\BlogController::class, 'store'])->middleware('auth:api');
    
        // Cập nhật bài viết (yêu cầu đăng nhập)
        Route::put('blog/{id}', [\App\Http\Controllers\Api\BlogController::class, 'update'])->middleware('auth:api');
    
        // Xóa bài viết (yêu cầu đăng nhập)
        Route::delete('blog/{id}', [\App\Http\Controllers\Api\BlogController::class, 'destroy'])->middleware('auth:api');
        Route::get('/blogcat', [\App\Http\Controllers\Api\BlogCategoryController::class, 'index']); // Lấy tất cả danh mục
        Route::get('/blogcat/{id}', [\App\Http\Controllers\Api\BlogCategoryController::class, 'show']); // Lấy chi tiết theo id
        Route::get('/my-blogs', [\App\Http\Controllers\Api\BlogController::class, 'getmyBlogs'])->middleware('auth:api'); // Lấy danh sách bài viết của người dùng
        Route::get('/blogs/user/{id}', [\App\Http\Controllers\Api\BlogController::class, 'getBlogsByUser']);
        Route::get('blogs/approved', [\App\Http\Controllers\Api\BlogController::class, 'getApprovedBlogs'])->middleware('auth:api');
        // Lấy danh sách bài viết đã duyệt
    // Social interactions
    // Likes
    Route::post('likes/toggle', [\App\Http\Controllers\Api\LikeController::class, 'toggle'])->middleware('auth:api');

    // Bookmarks
    Route::post('bookmarks/toggle', [\App\Http\Controllers\Api\BookmarkController::class, 'toggle'])->middleware('auth:api')->name('bookmarks.toggle');

    // Votes (Rating)
    Route::post('vote', [\App\Http\Controllers\Api\VoteController::class, 'vote'])->middleware('auth:api');
    Route::get('votes/average/{type}/{id}', [\App\Http\Controllers\Api\VoteController::class, 'average'])->middleware('auth:api');

    // Notifications
    Route::get('notifications', [\App\Http\Controllers\Api\NotificationController::class, 'index'])->middleware('auth:api')->name('notifications.index');
    Route::post('notifications/mark-read', [\App\Http\Controllers\Api\NotificationController::class, 'markAsRead'])->middleware('auth:api')->name('notifications.markRead');

    // Tags
    Route::get('blogs/{id}/tags', [\App\Http\Controllers\Api\BlogController::class, 'getTags']); // Lấy tags của blog
    Route::get('events/{id}/tags', [\App\Http\Controllers\Api\EventController::class, 'getTags']); // Lấy tags của event
    
    // Tag management
    Route::get('tags', [\App\Http\Controllers\TagController::class, 'index']); // Lấy tất cả tags
    Route::get('tags/{id}', [\App\Http\Controllers\TagController::class, 'show']); // Chi tiết tag
    Route::post('tags', [\App\Http\Controllers\TagController::class, 'store'])->middleware('auth:api'); // Tạo tag mới
    Route::put('tags/{id}', [\App\Http\Controllers\TagController::class, 'update'])->middleware('auth:api'); // Cập nhật tag
    Route::delete('tags/{id}', [\App\Http\Controllers\TagController::class, 'destroy'])->middleware('auth:api'); // Xóa tag
    
    Route::post('blogs/{id}/tags', [\App\Http\Controllers\Api\BlogController::class, 'attachTags'])->middleware('auth:api'); // Gắn tags vào blog
    Route::post('events/{id}/tags', [\App\Http\Controllers\Api\EventController::class, 'attachTags'])->middleware('auth:api'); // Gắn tags vào event
    
    Route::post('/events/{eventId}/images', [EventImageController::class, 'uploadEventImage']);
    Route::delete('/events/{eventId}/images/{resourceId}', [EventImageController::class, 'deleteEventImage']);
    
    Route::post('/like', [VoteController::class, 'like']);
    Route::post('/event-images/{resourceId}/toggle-like', [EventImageController::class, 'toggleLike'])->middleware('auth:api');
    
    Route::post('blogs/{id}/toggle-like', [BlogController::class, 'toggleLike'])->middleware('auth:api');
        
    Route::get('/statistics/top', [StatisticController::class, 'getTopStatistics']);
});

// Resource routes



