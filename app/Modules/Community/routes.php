// Routes cho bài đăng trong nhóm cụ thể
Route::get('group/{group_id}/posts', 'CommunityPostController@indexByGroup')->name('group.posts');
Route::post('members/system-role', 'CommunityMemberController@updateSystemRole')->name('members.system-role');

// Thêm các route mới cho quản lý thành viên
Route::post('members/role', 'CommunityMemberController@updateRole')->name('members.role');
Route::post('members/status', 'CommunityMemberController@updateStatus')->name('members.status'); 