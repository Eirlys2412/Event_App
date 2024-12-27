<?php

namespace App\Modules\Teaching_3\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Teaching_3\Models\LoaiChungchi;
use Illuminate\Http\Request;

class LoaiChungchiController extends Controller
{
    /**
     * Hiển thị danh sách loại chứng chỉ.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $loaiChungchis = LoaiChungchi::all();
        $active_menu = 'loai_chungchi_list'; // Thêm active menu
        return view('Teaching_3::LoaiChungchi.index', compact('loaiChungchis', 'active_menu'));
    }

    /**
     * Hiển thị form tạo mới loại chứng chỉ.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $active_menu = 'loai_chungchi_add'; // Thêm active menu
        return view('Teaching_3::LoaiChungchi.create', compact('active_menu'));
    }

    /**
     * Lưu loại chứng chỉ mới vào cơ sở dữ liệu.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Xác thực dữ liệu người dùng nhập vào
        $request->validate([
            'title' => 'required',
            'status' => 'required|in:active,inactive',
        ]);

        // Lưu vào cơ sở dữ liệu
        LoaiChungchi::create($request->all());

        // Quay lại trang danh sách và hiển thị thông báo thành công
        return redirect()->route('loai_chungchi.index')->with('success', 'Loại chứng chỉ đã được tạo thành công.');
    }

    /**
     * Hiển thị form chỉnh sửa loại chứng chỉ.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        // Lấy thông tin loại chứng chỉ cần chỉnh sửa
        $loaiChungchi = LoaiChungchi::findOrFail($id);
        $active_menu = 'loai_chungchi_edit'; // Thêm active menu
        return view('Teaching_3::LoaiChungchi.edit', compact('loaiChungchi', 'active_menu'));
    }

    /**
     * Cập nhật loại chứng chỉ trong cơ sở dữ liệu.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Xác thực dữ liệu người dùng nhập vào
        $request->validate([
            'title' => 'required',
            'status' => 'required|in:active,inactive',
        ]);

        // Tìm loại chứng chỉ cần cập nhật và lưu thay đổi
        $loaiChungchi = LoaiChungchi::findOrFail($id);
        $loaiChungchi->update($request->all());

        // Quay lại trang danh sách và hiển thị thông báo thành công
        return redirect()->route('loai_chungchi.index')->with('success', 'Loại chứng chỉ đã được cập nhật thành công.');
    }

    /**
     * Xóa loại chứng chỉ khỏi cơ sở dữ liệu.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        // Tìm loại chứng chỉ và xóa nó
        $loaiChungchi = LoaiChungchi::findOrFail($id);
        $loaiChungchi->delete();

        // Quay lại trang danh sách và hiển thị thông báo thành công
        return redirect()->route('loai_chungchi.index')->with('success', 'Loại chứng chỉ đã được xóa thành công.');
    }
}
