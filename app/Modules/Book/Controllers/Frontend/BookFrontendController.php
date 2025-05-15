<?php

namespace App\Modules\Book\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use App\Modules\Book\Models\Book;
use App\Modules\Resource\Models\Resource;
use Illuminate\Support\Facades\DB;

class BookFrontendController extends Controller
{
    // Hiển thị danh sách sách trên frontend
    public function index()
    {
        $books = Book::with('user', 'bookType')->paginate(10); 

        return view('Book::frontend.index', compact('books'));
    }

    // Hiển thị chi tiết sách trên frontend
    public function show($slug)
    {
        // Lấy sách theo slug
        $book = Book::with('user', 'bookType')->where('slug', $slug)->firstOrFail(); 

        // Lấy tài nguyên liên quan (Tương tự controller backend)
        $resourceIds = json_decode($book->resources, true)['resource_ids'] ?? [];
        $resources = Resource::whereIn('id', $resourceIds)->get();

        // Lấy các tag gắn với sách
        $tags = DB::table('tag_books')->where('book_id', $book->id)->pluck('tag_id');
        $tagNames = Tag::whereIn('id', $tags)->pluck('title');

        return view('Book::frontend.show', compact('book', 'resources', 'tagNames'));
    }
}
