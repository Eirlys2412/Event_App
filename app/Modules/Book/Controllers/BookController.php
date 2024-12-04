<?php

namespace App\Modules\Book\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use App\Modules\Book\Models\Book;
use App\Modules\Book\Models\BookType;
use App\Modules\Resource\Models\Resource;
use App\Modules\Resource\Models\ResourceType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BookController extends Controller
{
    protected $pagesize;

    public function __construct()
    {
        $this->pagesize = env('NUMBER_PER_PAGE', '20');
        $this->middleware('auth');
    }

    public function index()
    {
        $books = Book::with('user')->paginate($this->pagesize);

        foreach ($books as $book) {
            $resourceIds = json_decode($book->resources, true)['resource_ids'] ?? [];
            $resourceUrls = Resource::whereIn('id', $resourceIds)->pluck('url')->toArray();
            $book->setAttribute('resource_urls', $resourceUrls);
        }

        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Danh sách sách</li>';
        $active_menu = "book_list";

        return view('Book::books.index', compact('books', 'breadcrumb', 'active_menu'));
    }

    public function create()
    {
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Tạo sách</li>';
        $active_menu = "book_add";
        $tags = Tag::where('status', 'active')->orderBy('title', 'ASC')->get();
        $typeCodes = ResourceType::distinct()->pluck('code');
        $bookTypes = BookType::all();

        return view('Book::books.create', compact('breadcrumb', 'active_menu', 'typeCodes', 'tags', 'bookTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'photo' => 'nullable|file|image|max:204800',
            'summary' => 'nullable|string|max:1000',
            'content' => 'nullable|string',
            'document' => 'required|array',
            'document.*' => 'file|mimes:jpg,jpeg,png,mp4,mp3,pdf,doc,mov,docx,ppt,pptx,xls,xlsx|max:204800',
            'status' => 'required|in:active,inactive',
            'tag_ids' => 'nullable|array',
            'book_type_id' => 'required|exists:book_types,id',
        ]);

        $resourceIds = [];
        $photoPath = null;

        if ($request->hasFile('photo')) {
            $filesController = new \App\Http\Controllers\FilesController();
            $photoPath = $filesController->store($request->file('photo'), 'uploads/books');
        }

        foreach ($request->file('document') as $file) {
            $resourceIds[] = Resource::createResource($request, $file, 'Book')->id;
        }

        $slug = Str::slug($request->title);
        $originalSlug = $slug;
        $counter = 1;

        while (Book::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        $bookData = [
            'title' => $request->title,
            'slug' => $slug,
            'photo' => $photoPath,
            'summary' => $request->summary,
            'content' => $request->content,
            'status' => $request->status,
            'user_id' => auth()->id(),
            'book_type_id' => $request->book_type_id,
        ];

        $book = Book::create($bookData);

        $book->resources = json_encode([
            'book_id' => $book->id,
            'resource_ids' => $resourceIds,
        ]);
        $book->save();

        if ($request->tag_ids) {
            (new \App\Http\Controllers\TagController())->store_book_tag($book->id, $request->tag_ids);
        }

        return redirect()->route('admin.books.index')->with('success', 'Tạo sách thành công.');
    }

    public function edit($id)
    {
        $book = Book::with('user')->findOrFail($id);
        $tags = Tag::where('status', 'active')->orderBy('title', 'ASC')->get();
        $tag_ids = DB::table('tag_books')->where('book_id', $book->id)->pluck('tag_id')->toArray();
        $resourceIds = json_decode($book->resources, true)['resource_ids'] ?? [];
        $resources = Resource::whereIn('id', $resourceIds)->get();
        $bookTypes = BookType::all();

        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Chỉnh sửa sách</li>';
        $active_menu = "book_edit";

        return view('Book::books.edit', compact('book', 'tags', 'tag_ids', 'resources', 'breadcrumb', 'active_menu', 'bookTypes'));
    }

    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'photo' => 'nullable|file|image|max:204800',
            'summary' => 'nullable|string|max:1000',
            'content' => 'nullable|string',
            'tag_ids' => 'nullable|array',
            'document' => 'nullable|array',
            'document.*' => 'file|mimes:jpg,jpeg,png,mp4,mp3,pdf,doc,mov,docx,ppt,pptx,xls,xlsx|max:204800',
            'status' => 'required|in:active,inactive',
            'book_type_id' => 'required|exists:book_types,id',
        ]);

        // Update photo if uploaded
        if ($request->hasFile('photo')) {
            if ($book->photo) {
                Storage::disk('public')->delete($book->photo);
            }

            $filesController = new \App\Http\Controllers\FilesController();
            $book->photo = $filesController->store($request->file('photo'), 'uploads/books');
        }

        // Handle existing and new resources
        $existingResources = json_decode($book->resources, true) ?? [];
        $existingResourceIds = $existingResources['resource_ids'] ?? [];
        $newResourceIds = [];

        if ($request->hasFile('document')) {
            foreach ($request->file('document') as $file) {
                // Check if file already exists in resources
                $existingResource = Resource::where('file_name', $file->getClientOriginalName())->first();
                if ($existingResource) {
                    // Skip if already linked
                    if (!in_array($existingResource->id, $existingResourceIds)) {
                        $newResourceIds[] = $existingResource->id;
                    }
                } else {
                    // Add new resource
                    $resource = Resource::createResource($request, $file, 'Book');
                    $newResourceIds[] = $resource->id;
                }
            }
        }

        // Merge new and existing resource IDs
        $finalResourceIds = array_unique(array_merge($existingResourceIds, $newResourceIds));

        // Update book details
        $book->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'summary' => $request->summary,
            'content' => $request->content,
            'status' => $request->status,
            'book_type_id' => $request->book_type_id,
        ]);

        // Save updated resources
        $book->resources = json_encode([
            'book_id' => $book->id,
            'resource_ids' => $finalResourceIds,
        ]);
        $book->save();

        // Update tags
        if ($request->has('tag_ids')) {
            (new \App\Http\Controllers\TagController())->update_book_tag($book->id, $request->tag_ids);
        }

        return redirect()->route('admin.books.index')->with('success', 'Cập nhật sách thành công.');
    }

    public function removeResource(Request $request, $bookId, $resourceId)
    {
        $resource = Resource::findOrFail($resourceId);
        if (file_exists(public_path($resource->url))) {
            unlink(public_path($resource->url));
        }
        $resource->delete();

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        if ($book->photo) {
            Storage::disk('public')->delete($book->photo);
        }
        $book->delete();

        return redirect()->route('admin.books.index')->with('success', 'Xóa sách thành công.');
    }

    public function bookStatus(Request $request)
    {

        $status = $request->mode == 'true' ? 'active' : 'inactive';
        DB::table('books')->where('id', $request->id)->update(['status' => $status]);

        return response()->json(['msg' => "Cập nhật thành công", 'status' => true]);
    }
    public function bookSearch(Request $request)
    {
        if ($request->datasearch) {
            $active_menu = "book_list";
            $searchdata = $request->datasearch;
            $books = Book::with('user')
                ->where('title', 'LIKE', '%' . $searchdata . '%')
                ->paginate($this->pagesize)
                ->withQueryString();
            foreach ($books as $book) {
                $resourceData = json_decode($book->resources, true);
                if (isset($resourceData['resource_ids'])) {
                    $resourceUrls = [];
                    foreach ($resourceData['resource_ids'] as $resourceId) {
                        $resource = Resource::find($resourceId);
                        if ($resource) {
                            $resourceUrls[] = $resource->url;
                        }
                    }
                    $book->setAttribute('resource_urls', $resourceUrls);
                } else {
                    $book->setAttribute('resource_urls', []);
                }
            }

            $breadcrumb = '
                <li class="breadcrumb-item"><a href="#">/</a></li>
                <li class="breadcrumb-item" aria-current="page"><a href="' . route('admin.books.index') . '">Sách</a></li>
                <li class="breadcrumb-item active" aria-current="page"> tìm kiếm </li>';

            return view('Book::books.search', compact('books', 'breadcrumb', 'searchdata', 'active_menu'));
        } else {
            return redirect()->route('admin.books.index')->with('success', 'Không có thông tin tìm kiếm!');
        }
    }

    public function show($id)
    {
        // Tìm sách theo id
        $book = Book::with('user', 'bookType')->findOrFail($id);

        // Lấy tài nguyên liên quan
        $resourceIds = json_decode($book->resources, true)['resource_ids'] ?? [];
        $resources = Resource::whereIn('id', $resourceIds)->get();

        // Lấy các tag gắn với sách
        $tags = DB::table('tag_books')->where('book_id', $book->id)->pluck('tag_id');
        $tagNames = Tag::whereIn('id', $tags)->pluck('title');

        // Lấy thông tin các loại tài liệu (nếu cần)
        $bookTypes = BookType::all();

        // Chuẩn bị breadcrumb và menu hiện tại
        $breadcrumb = '
    <li class="breadcrumb-item"><a href="#">/</a></li>
    <li class="breadcrumb-item"><a href="' . route('admin.books.index') . '">Danh sách sách</a></li>
    <li class="breadcrumb-item active" aria-current="page">Chi tiết sách</li>';

        $active_menu = "book_show";

        // Trả về view hiển thị chi tiết sách
        return view('Book::books.show', compact('book', 'resources', 'tags', 'tagNames', 'breadcrumb', 'active_menu', 'bookTypes'));
    }
}
