<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Bookmark;

class BookmarkController extends Controller
{
    // POST /api/v1/bookmarks/toggle
    public function toggle(Request $request)
    {
        $data = $request->validate([
            'bookmarkable_type'=>'required|string',
            'bookmarkable_id'=>'required|integer',
        ]);
        $data['user_id'] = Auth::id();

        $exists = Bookmark::where($data)->exists();
        if ($exists) {
            Bookmark::where($data)->delete();
            $status = 'removed';
        } else {
            Bookmark::create($data);
            $status = 'added';
        }
        return response()->json(['status'=>$status], 200);
    }
} 