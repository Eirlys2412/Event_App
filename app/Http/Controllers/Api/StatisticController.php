<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Blog\Models\Blog;
use App\Modules\Events\Models\Event;
use App\Modules\Comments\Models\Comment;
use App\Modules\TuongTac\Models\Vote;
use App\Modules\TuongTac\Models\Like;
use App\Modules\Resource\Models\Resource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class StatisticController extends Controller
{
    public function getTopStatistics()
    {
        // Lấy top 5 sự kiện có điểm trung bình cao nhất
        $topEvents = Event::select('event.id', 'event.title', DB::raw('AVG(votes.rating) as average_rating'))
            ->leftJoin('votes', function($join) {
                $join->on('event.id', '=', 'votes.votable_id')
                    ->where('votes.votable_type', '=', 'App\\Modules\\Events\\Models\\Event');
            })
            ->groupBy('event.id', 'event.title')
            ->orderBy('average_rating', 'desc')
            ->limit(5)
            ->get()
            ->map(function($event) {
                // Bỏ thông tin user vì bảng event không có cột user_id
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'average_rating' => round($event->average_rating, 2),
                ];
            });

        // Lấy top 5 bài viết có nhiều lượt thích nhất
        $topBlogs = Blog::select('blogs.id', 'blogs.title', DB::raw('COUNT(likes.id) as total_likes'), 'users.full_name as author_name')
            ->leftJoin('likes', function($join) {
                $join->on('blogs.id', '=', 'likes.likeable_id')
                    ->where('likes.likeable_type', '=', 'App\\Modules\\Blog\\Models\\Blog');
            })
            ->leftJoin('users', 'blogs.user_id', '=', 'users.id')
            ->groupBy('blogs.id', 'blogs.title', 'author_name') // Group by author_name after join
            ->orderBy('total_likes', 'desc')
            ->limit(5)
            ->get()
            ->map(function($blog) {
                return [
                    'id' => $blog->id,
                    'title' => $blog->title,
                    'total_likes' => $blog->total_likes,
                    'author_name' => $blog->author_name ?? 'Unknown', // Sử dụng author_name từ join
                ];
            });

        // Lấy top 5 bình luận có nhiều lượt thích nhất
        $topComments = Comment::select('comments.id', 'comments.content', DB::raw('COUNT(likes.id) as total_likes'), 'users.full_name as author_name')
            ->leftJoin('likes', function($join) {
                $join->on('comments.id', '=', 'likes.likeable_id')
                    ->where('likes.likeable_type', '=', 'App\\Modules\\Comments\\Models\\Comment');
            })
            ->leftJoin('users', 'comments.user_id', '=', 'users.id')
            ->groupBy('comments.id', 'comments.content', 'author_name') // Group by author_name after join
            ->orderBy('total_likes', 'desc')
            ->limit(5)
            ->get()
            ->map(function($comment) {
                return [
                    'id' => $comment->id,
                    'content' => $comment->content,
                    'total_likes' => $comment->total_likes,
                    'author_name' => $comment->author_name ?? 'Unknown', // Sử dụng author_name từ join
                ];
            });

        // Lấy top 5 hình ảnh sự kiện có nhiều lượt thích nhất
        $topEventImages = Resource::select('resources.id', 'resources.url', 'resources.title', DB::raw('COUNT(likes.id) as total_likes'), 'users.full_name as author_name')
            ->leftJoin('likes', function($join) {
                $join->on('resources.id', '=', 'likes.likeable_id')
                    ->where('likes.likeable_type', '=', 'App\\Modules\\Resource\\Models\\Resource');
            })
            ->leftJoin('users', 'resources.user_id', '=', 'users.id')
            ->where('resources.code', 'Event')
            ->where('resources.file_type', 'like', 'image/%')
            ->groupBy('resources.id', 'resources.url', 'resources.title', 'author_name') // Group by author_name after join
            ->orderBy('total_likes', 'desc')
            ->limit(5)
            ->get()
            ->map(function($image) {
                return [
                    'id' => $image->id,
                    'title' => $image->title,
                    'url' => URL::to($image->url),
                    'total_likes' => $image->total_likes,
                    'author_name' => $image->author_name ?? 'Unknown', // Sử dụng author_name từ join
                ];
            });

        // Thống kê tổng quan
        $overallStats = [
            'total_event' => Event::count(),
            'total_blogs' => Blog::count(),
            'total_comments' => Comment::count(),
            'total_likes' => Like::count(),
            'total_votes' => Vote::count(),
            'average_event_rating' => round(Vote::where('votable_type', 'App\\Modules\\Events\\Models\\Event')->avg('rating'), 2)
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'top_event' => $topEvents,
                'top_blogs' => $topBlogs,
                'top_comments' => $topComments,
                'top_event_images' => $topEventImages,
                'overall_stats' => $overallStats
            ]
        ]);
    }
} 