<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

// Api 
Route::group(['namespace' => 'api', 'prefix' => 'v1'], function () {

    // Authentication
    Route::post('login', [\App\Http\Controllers\Api\AuthenticationController::class, 'store']);
    Route::post('logout', [\App\Http\Controllers\Api\AuthenticationController::class, 'destroy'])->middleware('auth:api');
    Route::post('register', [\App\Http\Controllers\Api\AuthenticationController::class, 'savenewUser']);
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
    Route::get('/users/{id}', [\App\Http\Controllers\Api\ApiUserController::class, 'show']);


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

    Route::get('/event-types', [\App\Http\Controllers\Api\EventTypeController::class, 'index']); // Lấy danh sách loại sự kiện
    Route::get('/event-types/{id}', [\App\Http\Controllers\Api\EventTypeController::class, 'getEventTypeById']); // Lấy chi tiết loại sự kiện
    Route::post('/event-types', [\App\Http\Controllers\Api\EventTypeController::class, 'store']); // Tạo loại sự kiện
    Route::put('/event-types/{id}', [\App\Http\Controllers\Api\EventTypeController::class, 'update']); // Cập nhật loại sự kiện
    Route::delete('/event-types/{id}', [\App\Http\Controllers\Api\EventTypeController::class, 'destroy']); // Xóa loại sự kiện
    
    Route::get('/event-users', [\App\Http\Controllers\Api\EventUserApiController::class, 'index']); // Danh sách người dùng tham gia sự kiện
    Route::post('/event-users', [\App\Http\Controllers\Api\EventUserApiController::class, 'store']); // Thêm mới người dùng vào sự kiện
    Route::get('/event-users/{id}', [\App\Http\Controllers\Api\EventUserApiController::class, 'show']); // Xem chi tiết 1 người dùng sự kiện
    Route::put('/event-users/{id}', [\App\Http\Controllers\Api\EventUserApiController::class, 'update']); // Cập nhật
    Route::delete('/event-users/{id}', [\App\Http\Controllers\Api\EventUserApiController::class, 'destroy']); // Xóa
    
    Route::post('/join', [\App\Http\Controllers\Api\EventUserApiController::class, 'joinEvent']); // Người dùng tham gia sự kiện
    Route::get('/event/{eventId}/participants', [\App\Http\Controllers\Api\EventUserApiController::class, 'listParticipants']); // Lấy danh sách người tham gia sự kiện theo event_id
      
    Route::get('/event-attendance', [\App\Http\Controllers\Api\EventAttendanceApiController::class, 'index']);// lấy danh sách sự kiện đã điểm danh
    Route::post('/event-attendance', [\App\Http\Controllers\Api\EventAttendanceApiController::class, 'store']);// điểm danh sự kiện
    Route::get('/event-attendance/{id}', [\App\Http\Controllers\Api\EventAttendanceApiController::class, 'show']);// xem chi tiết sự kiện đã điểm danh
    Route::put('/event-attendance/{id}', [\App\Http\Controllers\Api\EventAttendanceApiController::class, 'update']);// cập nhật sự kiện đã điểm danh
    Route::delete('/event-attendance/{id}', [\App\Http\Controllers\Api\EventAttendanceApiController::class, 'destroy']);// xóa sự kiện đã điểm danh
    Route::post('/check-in/{eventId}', [\App\Http\Controllers\Api\EventAttendanceApiController::class, 'checkInByQr']);// điểm danh sự kiện bằng mã QR

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
    Route::get('comments', [\App\Http\Controllers\Api\CommentController::class, 'index'])->name('comments.index');// lấy danh sách bình luận
    Route::post('comments', [\App\Http\Controllers\Api\CommentController::class, 'store'])->middleware('auth:api')->name('comments.store');// tạo bình luận
    Route::put('comments/{id}', [\App\Http\Controllers\Api\CommentController::class, 'update'])->middleware('auth:api')->name('comments.update');  // cập nhật bình luận
    Route::delete('comments/{id}', [\App\Http\Controllers\Api\CommentController::class, 'destroy'])->middleware('auth:api')->name('comments.destroy');// xóa bình luận
    
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
    Route::post('luubai2', [\App\Http\Controllers\Api\BlogController::class, 'store'])->middleware('auth:api');// tạo bài viết
    Route::get('blog', [\App\Http\Controllers\Api\BlogController::class, 'getblog']) ;// lấy danh sách bài viết
    Route::get('blogcat', [\App\Http\Controllers\Api\BlogController::class, 'getBlogCat']) ;// lấy danh sách danh mục bài viết
    Route::get('blogsearch', [\App\Http\Controllers\Api\BlogController::class, 'getBlogSearch']) ;// tìm kiếm bài viết
    Route::post('/create-post', [\App\Http\Controllers\Api\BlogController::class, 'createPost']);// tạo bài viết    
    Route::get('blog/{id}', [\App\Http\Controllers\Api\BlogController::class, 'getBlogById']);// lấy chi tiết bài viết


    // Social interactions
    // Likes
    Route::post('likes/toggle', [\App\Http\Controllers\Api\LikeController::class, 'toggle'])->middleware('auth:api')->name('likes.toggle');

    // Bookmarks
    Route::post('bookmarks/toggle', [\App\Http\Controllers\Api\BookmarkController::class, 'toggle'])->middleware('auth:api')->name('bookmarks.toggle');

    // Votes (Rating)
    Route::post('votes', [\App\Http\Controllers\Api\VoteController::class, 'store'])->middleware('auth:api')->name('votes.store');
    Route::get('votes/average', [\App\Http\Controllers\Api\VoteController::class, 'average'])->middleware('auth:api')->name('votes.average');

    // Notifications
    Route::get('notifications', [\App\Http\Controllers\Api\NotificationController::class, 'index'])->middleware('auth:api')->name('notifications.index');
    Route::post('notifications/mark-read', [\App\Http\Controllers\Api\NotificationController::class, 'markAsRead'])->middleware('auth:api')->name('notifications.markRead');
});
    
    



