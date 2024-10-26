<?php

namespace App\Modules\Motion\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Motion\Models\MotionItem;
use App\Modules\Motion\Models\Motion;

class MotionItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($motion_id)
    {
        $motion = Motion::find($motion_id);
        $items = MotionItem::where('motion_id', $motion_id)->get();

        return view('Motion::motion_item.index', compact('items', 'motion'));
    }

    public function create($motion_id)
    {
        $motion = Motion::find($motion_id);

        return view('Motion::motion_item.create', compact('motion'));
    }

    public function store(Request $request, $motion_id)
    {
        $this->validate($request, [
            'id_item' => 'integer|required',
            'item_code' => 'string|required',
            'count' => 'integer|required',
        ]);

        $data = $request->all();
        $data['motion_id'] = $motion_id;
        MotionItem::create($data);

        return redirect()->route('motion.item.index', $motion_id)->with('success', 'Tạo motion item thành công!');
    }

    public function edit($motion_id, $id)
    {
        $motion = Motion::find($motion_id);
        $item = MotionItem::find($id);

        return view('Motion::motion_item.edit', compact('item', 'motion'));
    }

    public function update(Request $request, $motion_id, $id)
    {
        $this->validate($request, [
            'id_item' => 'integer|required',
            'item_code' => 'string|required',
            'count' => 'integer|required',
        ]);

        $item = MotionItem::find($id);
        $item->update($request->all());

        return redirect()->route('motion.item.index', $motion_id)->with('success', 'Cập nhật motion item thành công!');
    }

    public function destroy($motion_id, $id)
    {
        MotionItem::destroy($id);

        return redirect()->route('motion.item.index', $motion_id)->with('success', 'Xóa motion item thành công!');
    }
}
