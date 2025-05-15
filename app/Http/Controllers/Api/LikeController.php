<?php 
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function toggle(Request $request)
    {
        $request->validate([
            'likeable_type' => 'required|string', // 'blog' hoặc 'event'
            'likeable_id' => 'required|integer',
        ]);

        $likeableType = 'App\\Models\\' . ucfirst($request->likeable_type);
        $like = Like::where([
            'user_id' => Auth::id(),
            'likeable_type' => $likeableType,
            'likeable_id' => $request->likeable_id
        ])->first();

        if ($like) {
            $like->delete();
            return response()->json(['liked' => false]);
        } else {
            Like::create([
                'user_id' => Auth::id(),
                'likeable_type' => $likeableType,
                'likeable_id' => $request->likeable_id,
            ]);
            return response()->json(['liked' => true]);
        }
    }
}
