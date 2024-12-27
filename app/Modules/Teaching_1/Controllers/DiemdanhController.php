<?php

namespace App\Modules\Teaching_1\Controllers;

use App\Modules\Teaching_1\Models\Diemdanh;
use App\Modules\Teaching_1\Models\Student;
use App\Modules\Teaching_2\Models\HocPhan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class DiemdanhController extends Controller
{
    /**
     * Hiển thị danh sách điểm danh
     */
    // Ví dụ: Trong DiemdanhController

    

public function index()
{
    // Truyền biến active_menu vào view
    $active_menu = 'diemdanh_list'; // Đặt active_menu cho trang danh sách điểm danh
    
    // Lấy danh sách điểm danh với quan hệ sinhvien và hocphan
    $diemdanh = Diemdanh::with(['student', 'hocphan'])->paginate(10);

    // Chuyển đổi trường 'time' thành đối tượng Carbon trước khi gửi vào view
    $diemdanh->each(function($item) {
        $item->time = Carbon::parse($item->time);  // Chuyển chuỗi thành đối tượng Carbon
    });


    // Trả về view với dữ liệu
    return view('Teaching_1::diemdanh.index', compact('diemdanh', 'active_menu'));
}

    

public function create()
{
    $sinhviens = Student::all(); // Lấy tất cả sinh viên
    $hocphans = HocPhan::all(); // Lấy tất cả học phần
    $active_menu = 'diemdanh_add'; // Thiết lập active menu
    return view('Teaching_1::diemdanh.create', compact('sinhviens', 'hocphans', 'active_menu'));
}



    /**
     * Lưu thông tin điểm danh
     */
    public function store(Request $request)
{
    // Xử lý form data
    $diemdanh = new Diemdanh();
    $diemdanh->sinhvien_id = $request->sinhvien_id;
    $diemdanh->hocphan_id = $request->hocphan_id;
    $diemdanh->time = Carbon::parse($request->time); // Nếu bạn cần chuyển thời gian
    $diemdanh->trangthai = $request->trangthai;
    $diemdanh->save();

    return redirect()->route('diemdanh.index');
}

// public function update(Request $request, $id)
// {
//     // Kiểm tra dữ liệu đầu vào trước khi xử lý
//     $validated = $request->validate([
//         'sinhvien_id' => 'required|exists:students,id', 
//         'hocphan_id' => 'required|exists:hocphans,id',
//         'time' => 'required|date',
//         'trangthai' => 'required|in:có mặt,vắng mặt,muộn',
//     ]);

//     // Tìm điểm danh theo ID
//     $diemdanh = Diemdanh::findOrFail($id);

//     // Cập nhật các trường với dữ liệu mới
//     $diemdanh->sinhvien_id = $validated['sinhvien_id'];
//     $diemdanh->hocphan_id = $validated['hocphan_id'];
//     $diemdanh->time = Carbon::parse($validated['time'])->format('Y-m-d H:i:s');
//     $diemdanh->trangthai = $validated['trangthai'];

//     // Lưu thay đổi vào cơ sở dữ liệu
//     $diemdanh->save();

//     // Redirect về trang danh sách điểm danh với thông báo thành công
//     return redirect()->route('diemdanh.index')->with('success', 'Cập nhật điểm danh thành công.');
// }




    /**
     * Hiển thị form chỉnh sửa
     */
    public function edit($diemdanh_id)
{
    // return 'hello';
    $diemdanh = Diemdanh::findOrFail($diemdanh_id);
    $sinhviens = Student::all();  // Hoặc các dữ liệu bạn cần
    $hocphans = HocPhan::all();  // Hoặc các dữ liệu bạn cần

    // Đảm bảo truyền biến active_menu vào view
    $active_menu = 'diemdanh_edit'; // Hoặc một giá trị nào đó bạn muốn để đánh dấu menu đang hoạt động

    return view('Teaching_1::diemdanh.edit', compact('diemdanh', 'sinhviens', 'hocphans', 'active_menu'));
}

public function update(Request $request, $diemdanh_id){
    // // Kiểm tra dữ liệu đầu vào trước khi xử lý
    // $validated = $request->validate([
    //     'sinhvien_id' => 'required|exists:students,id', 
    //     'hocphan_id' => 'required|exists:hocphans,id',
    //     'time' => 'required|date',
    //     'trangthai' => 'required|in:có mặt,vắng mặt,muộn',
    // ]);

    // // Tìm điểm danh theo ID
    // $diemdanh = Diemdanh::findOrFail($diemdanh_id);

    // // Cập nhật các trường với dữ liệu mới
    // $diemdanh->sinhvien_id = $validated['sinhvien_id'];
    // $diemdanh->hocphan_id = $validated['hocphan_id'];
    // $diemdanh->time = Carbon::parse($validated['time'])->format('Y-m-d H:i:s');
    // $diemdanh->trangthai = $validated['trangthai'];

    // // Lưu thay đổi vào cơ sở dữ liệu
    // $diemdanh->save();

    // // Redirect về trang danh sách điểm danh với thông báo thành công
    // return redirect()->route('diemdanh.index')->with('success', 'Cập nhật điểm danh thành công.');
    // Xác thực dữ liệu nhập vào
    $validated = $request->validate([
        'sinhvien_id' => 'required|exists:students,id', 
        'hocphan_id' => 'required|exists:hoc_phans,id',
        'time' => 'required|date',
        'trangthai' => 'required|in:có mặt,vắng mặt,muộn',
    ]);

    // Tìm bản ghi theo ID
    $diemdanh = Diemdanh::findOrFail($diemdanh_id);

    // Lấy tất cả dữ liệu từ yêu cầu
    $requestData = $request->all();

   

    // Cập nhật dữ liệu vào cơ sở dữ liệu
    $diemdanh->update($requestData);

    return redirect()->route('diemdanh.index')->with('success', 'Cập nhật điểm danh thành công.');

}
    /**
     * Xóa một bản ghi
     */
    public function destroy($id)
{
    $diemdanh = Diemdanh::findOrFail($id);
    $diemdanh->delete();

    return redirect()->route('diemdanh.index')->with('success', 'Xóa điểm danh thành công.');
}
}
