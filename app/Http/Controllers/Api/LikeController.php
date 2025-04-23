<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Like;

class LikeController extends Controller
{
    // POST /api/v1/likes/toggle
    public function toggle(Request $request)
    {
        $data = $request->validate([
            'likeable_type'=>'required|string',
            'likeable_id'=>'required|integer',
        ]);
        $data['user_id'] = Auth::id();

        $exists = Like::where($data)->exists();
        if ($exists) {
            Like::where($data)->delete();
            $status = 'removed';
        } else {
            Like::create($data);
            $status = 'added';
        }
        return response()->json(['status'=>$status], 200);
    }
} 