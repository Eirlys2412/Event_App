<?php

namespace App\Modules\Teaching_2\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Teaching_1\Models\ClassModel;
use Illuminate\Http\Request;
use App\Modules\Teaching_2\Models\PhanCong;
use App\Modules\Teaching_1\Models\teacher;
use App\Modules\Teaching_2\Models\HocPhan;
use App\Modules\Teaching_2\Models\HocKy;
use App\Modules\Teaching_2\Models\NamHoc;

class PhanCongController extends Controller
{
    /**
     * Hiển thị danh sách phân công.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Lấy danh sách phân công với liên kết các bảng giảng viên, học phần, học kỳ, năm học
        $phancongs = PhanCong::with(['giangvien:id,mgv', 'hocphan', 'hocky', 'namhoc'])->paginate(10);

        $active_menu = 'phancong';

        return view('Teaching_2::phancong.index', compact('phancongs', 'active_menu'));
    }

    /**
     * Hiển thị form tạo phân công mới.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $giangviens = Teacher::with('user')->get(); 
        $hocphans = HocPhan::all();
        $hockys = HocKy::all();
        $namhocs = NamHoc::all();
        $classes = ClassModel::all();
        
        $active_menu = 'phancong';
        

        return view('Teaching_2::phancong.create', compact('giangviens', 'hocphans', 'hockys', 'namhocs', 'classes','active_menu'));
    }

    /**
     * Lưu phân công mới vào cơ sở dữ liệu.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
{
    // Validate dữ liệu
    $request->validate([
        'giangvien_id' => 'required|exists:teacher,id', // Đảm bảo tên bảng đúng
        'hocphan_id' => 'required|exists:hoc_phans,id',
        'hocky_id' => 'required|exists:hoc_ky,id',
        'namhoc_id' => 'required|exists:nam_hoc,id',
        'ngayphancong' => 'required|date',
        'time_start' => 'nullable|date',
        'time_end' => 'nullable|date',
        'class_id' => 'required|exists:classes,id',
        'max_student' =>  'required|integer|min:0',
    ]);

    // Tìm giảng viên dựa trên mã giảng viên (mgv)
    $giangvien = Teacher::where('id', $request->giangvien_id)->first();

    if (!$giangvien) {
        // Nếu không tìm thấy giảng viên, trả về thông báo lỗi
        return redirect()->route('phancong.create')->with('error', 'Mã giảng viên không tồn tại!');
    }

    // Tạo phân công mới
    PhanCong::create([
        'giangvien_id' => $giangvien->id,  // Lấy ID giảng viên từ kết quả tìm kiếm
        'hocphan_id' => $request->hocphan_id,
        'hocky_id' => $request->hocky_id,
        'namhoc_id' => $request->namhoc_id,
        'ngayphancong' => $request->ngayphancong,
        'time_start' => $request->time_start,
        'time_end' => $request->time_end,
        'class_id' => $request->class_id,
        'max_student' => $request->max_student,
    ]);

    return redirect()->route('phancong.index')->with('success', 'Phân công mới đã được tạo!');
}


    /**
     * Hiển thị form chỉnh sửa phân công.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
{
    // Lấy phân công cần chỉnh sửa
    $phancong = PhanCong::findOrFail($id);

    // Lấy danh sách giảng viên, học phần, học kỳ và năm học
    $giangviens = Teacher::with('user')->get(); 
    $hocphans = HocPhan::all();
    $hockys = HocKy::all();
    $namhocs = NamHoc::all();
    $classes = ClassModel::all();

    $active_menu = 'phancong';

    // Trả về view với dữ liệu cần thiết
    return view('Teaching_2::phancong.edit', compact('phancong', 'giangviens', 'hocphans', 'hockys', 'namhocs','classes', 'active_menu'));
}

    /**
     * Cập nhật phân công vào cơ sở dữ liệu.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
{
    // Validate dữ liệu
    $request->validate([
        'giangvien_id' => 'required|exists:teacher,id',  // Mã giảng viên phải tồn tại trong bảng teacher
        'hocphan_id' => 'required|exists:hoc_phans,id',
        'hocky_id' => 'required|exists:hoc_ky,id',
        'namhoc_id' => 'required|exists:nam_hoc,id',
        'ngayphancong' => 'required|date',
        'time_start' => 'nullable|date',
        'time_end' => 'nullable|date',
        'class_id' => 'required|exists:classes,id',
        'max_student' =>  'required|integer|min:0',

    ]);

    // Lấy phân công cần cập nhật
    $phancong = PhanCong::findOrFail($id);

    // Tìm giảng viên dựa trên mã giảng viên (mgv)
    $giangvien = Teacher::where('id', $request->giangvien_id)->first();

    if (!$giangvien) {
        // Nếu không tìm thấy giảng viên, trả về thông báo lỗi
        return redirect()->route('phancong.edit', ['id' => $id])->with('error', 'Mã giảng viên không tồn tại!');
    }

    // Cập nhật phân công
    $phancong->update([
        'giangvien_id' => $giangvien->id,  // Lấy ID giảng viên từ kết quả tìm kiếm
        'hocphan_id' => $request->hocphan_id,
        'hocky_id' => $request->hocky_id,
        'namhoc_id' => $request->namhoc_id,
        'ngayphancong' => $request->ngayphancong,
        'time_start' => $request->time_start,
        'time_end' => $request->time_end,
        'class_id' => $request->class_id,
        'max_student' => $request->max_student,
    ]);

    // Chuyển hướng về danh sách phân công với thông báo thành công
    return redirect()->route('phancong.index')->with('success', 'Phân công đã được cập nhật!');
}
    /**
     * Xóa phân công khỏi cơ sở dữ liệu.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $phancong = PhanCong::find($id);
        if (!$phancong) {
            return redirect()->route('phancong.index')->with('error', 'Không tìm thấy phân công!');
        }

        $phancong->delete();

        return redirect()->route('phancong.index')->with('success', 'Phân công đã được xóa!');
    }
}
