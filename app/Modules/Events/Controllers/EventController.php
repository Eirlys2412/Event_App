<?php

namespace App\Modules\Events\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Events\Models\Event;
use App\Modules\Events\Models\EventType;
use App\Models\Tag;
use App\Modules\Events\Models\TagEvent;
use App\Modules\Resource\Models\Resource;
use Illuminate\Support\Facades\DB;


class EventController extends Controller
{
    protected $pagesize;

    public function __construct()
    {
        $this->pagesize = env('NUMBER_PER_PAGE', 20);
        $this->middleware('auth');
    }

    // Danh sách các sự kiện
    public function index()
{
    $eventList = Event::with('eventType')->paginate($this->pagesize); // Chỉ lấy eventType

    foreach ($eventList as $event) {
        $resourceIds = json_decode($event->resources, true)['resource_ids'] ?? [];
        $resourceUrls = Resource::whereIn('id', $resourceIds)->get(['id', 'url','title', 'file_type', 'type_code']);
        $event->resource_data = $resourceUrls;

    }

    $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Danh sách sự kiện</li>';
    $active_menu = "event_list";

    return view('Events::event.index', compact('eventList', 'breadcrumb', 'active_menu'));
}

    // Tạo mới sự kiện
    public function create()
{
    $eventTypes = EventType::all();
    $tags = Tag::where('status', 'active')->orderBy('title', 'ASC')->get();
    $active_menu = "event_add";
    $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item"><a href="' . route('admin.event.index') . '">Sự kiện</a></li>
        <li class="breadcrumb-item active" aria-current="page">Tạo Sự kiện</li>';

    return view('Events::event.create', compact('breadcrumb', 'active_menu', 'eventTypes', 'tags'));
}

// Lưu sự kiện mới
public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'summary' => 'nullable|string',
        'description' => 'nullable|string',
        'timestart' => 'required|date',
        'timeend' => 'required|date|after:timestart',
        'event_type_id' => 'required|exists:event_type,id', // Sửa tên bảng
        'tag_ids' => 'nullable|array',
        'documents.*' => 'file|mimes:jpg,jpeg,png,mp4,mp3,pdf,doc,mov,docx,ppt,pptx,xls,xlsx|max:204800',
    ]);

    // Tạo slug tự động từ title
    $slug = Str::slug($request->title);

    // Tạo sự kiện
    $event = Event::create($request->only([
        'title',
        'summary',
        'description',
        'timestart',
        'timeend',
        'event_type_id',
    ]) + ['slug' => $slug]);

    // Lưu tài liệu
    $resourceIds = [];
    if ($request->hasFile('documents')) {
        foreach ($request->file('documents') as $file) {
            $resource = Resource::createResource($request, $file, 'Event');
            $resourceIds[] = $resource->id;
        }
    }

    // Cập nhật tài liệu vào Event
    $event->resources = json_encode([
        'event_id' => $event->id,
        'resource_ids' => $resourceIds,
    ]);
    $event->save();

    // Lưu tag
    if ($request->has('tag_ids')) {
        (new \App\Http\Controllers\TagController())->store_event_tag($event->id, $request->tag_ids);
    }

    return redirect()->route('admin.event.index')->with('success', 'Sự kiện đã được tạo thành công!');
}

    // Chỉnh sửa sự kiện
    public function edit($id)
{
    $event = Event::findOrFail($id);
    $eventTypes = EventType::all();
    $tags = Tag::where('status', 'active')->orderBy('title', 'ASC')->get();
    $tag_ids = DB::table('tag_events')->where('event_id', $event->id)->pluck('tag_id')->toArray();

    $active_menu = 'event';
    $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item"><a href="' . route('admin.event.index') . '">Sự kiện</a></li>
        <li class="breadcrumb-item active" aria-current="page">Chỉnh sửa Sự kiện</li>';

    return view('Events::event.edit', compact('event', 'eventTypes', 'breadcrumb', 'active_menu', 'tags', 'tag_ids'));
}

// Cập nhật sự kiện
public function update(Request $request, $id)
{
    $event = Event::findOrFail($id);

    $request->validate([
        'title' => 'required|string|max:255',
        'summary' => 'nullable|string',
        'description' => 'nullable|string',
        'timestart' => 'required|date',
        'timeend' => 'required|date|after:timestart',
        'event_type_id' => 'required|exists:event_type,id', // Sửa tên bảng
        'tag_ids' => 'nullable|array',
        'documents.*' => 'file|mimes:jpg,jpeg,png,mp4,mp3,pdf,doc,mov,docx,ppt,pptx,xls,xlsx|max:204800',
    ]);

    // Tạo slug tự động từ title
    $slug = Str::slug($request->title);

    // Cập nhật sự kiện
    $event->update($request->only([
        'title',
        'summary',
        'description',
        'timestart',
        'timeend',
        'event_type_id',
    ]) + ['slug' => $slug]);

    // Xử lý tài liệu mới
    $resourceIds = json_decode($event->resources, true)['resource_ids'] ?? [];
    if ($request->hasFile('documents')) {
        foreach ($request->file('documents') as $file) {
            $resource = Resource::createResource($request, $file, 'Event');
            $resourceIds[] = $resource->id;
        }
    }

    // Lưu cập nhật tài liệu
    $event->resources = json_encode([
        'event_id' => $event->id,
        'resource_ids' => array_unique($resourceIds),
    ]);
    $event->save();

    // Cập nhật tag
    if ($request->has('tag_ids')) {
        (new \App\Http\Controllers\TagController())->update_event_tag($event->id, $request->tag_ids);
    }

 
    return redirect()->route('admin.event.index')->with('success', 'Sự kiện đã được cập nhật!');
}

    // Xóa tài nguyên của sự kiện
    public function removeResource($eventId, $resourceId)
    {
        // Tìm sự kiện
        $event = Event::findOrFail($eventId);
        
        // Tìm tài nguyên
        $resource = Resource::findOrFail($resourceId);
    
        // Xóa tài nguyên (sử dụng phương thức đã có trong model)
        if (!$resource->deleteResource()) {
            return response()->json(['success' => false, 'message' => 'Không thể xóa tài nguyên.'], 500);
        }
    
        // Cập nhật danh sách tài nguyên của sự kiện
        $resourceIds = json_decode($event->resources, true)['resource_ids'] ?? [];
        $resourceIds = array_filter($resourceIds, fn($id) => $id != $resourceId);
    
        // Cập nhật lại tài nguyên trong sự kiện
        $event->resources = json_encode([
            'event_id' => $event->id,
            'resource_ids' => array_values($resourceIds),
        ]);
        
        $event->save();
    
        // Trả về phản hồi JSON
        return response()->json(['success' => true, 'message' => 'Tài nguyên đã được xóa thành công.']);
    }

    // Xóa sự kiện
    public function destroy($id)
    {
        Event::destroy($id);

        return redirect()->route('admin.event.index')->with('success', 'Sự kiện đã được xóa thành công.');
    }
}