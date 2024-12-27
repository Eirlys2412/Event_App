<?php

namespace App\Modules\Teaching_3\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Teaching_3\Models\PhancongGroup;
use App\Modules\Group\Models\Group;
use App\Modules\Teaching_2\Models\Phancong;
use Illuminate\Http\Request;

class PhancongGroupController extends Controller
{
    public function index()
    {
        $phancongGroups = PhancongGroup::with(['group', 'phancong'])->get();
        $active_menu = 'phancong_list'; // Đặt giá trị active_menu
        return view('Teaching_3::phanconggroup.index', compact('phancongGroups', 'active_menu'));
    }

    public function create()
    {
        $groups = Group::all();
        $phancongs = Phancong::all();
        $active_menu = 'phanconggroup_add'; // Đặt giá trị active_menu cho form tạo mới
        return view('Teaching_3::phanconggroup.create', compact('groups', 'phancongs', 'active_menu'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
            'phancong_id' => 'required|exists:phancong,id',
        ]);

        PhancongGroup::create($request->all());
        return redirect()->route('phanconggroup.index')->with('success', 'Phân công group đã được tạo thành công.');
    }

    public function edit($id)
    {
        $phancongGroup = PhancongGroup::findOrFail($id);
        $groups = Group::all();
        $phancongs = Phancong::all();
        $active_menu = 'phanconggroup_edit'; // Đặt giá trị active_menu cho form chỉnh sửa
        return view('Teaching_3::phanconggroup.edit', compact('phancongGroup', 'groups', 'phancongs', 'active_menu'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
            'phancong_id' => 'required|exists:phancong,id',
        ]);

        $phancongGroup = PhancongGroup::findOrFail($id);
        $phancongGroup->update($request->all());
        return redirect()->route('phanconggroup.index')->with('success', 'Phân công group đã được cập nhật thành công.');
    }

    public function destroy($id)
    {
        $phancongGroup = PhancongGroup::findOrFail($id);
        $phancongGroup->delete();
        return redirect()->route('phanconggroup.index')->with('success', 'Phân công group đã được xóa thành công.');
    }
}
