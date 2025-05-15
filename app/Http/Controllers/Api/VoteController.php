<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vote;
use Illuminate\Support\Facades\Auth;


class VoteController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'votable_type' => 'required|string', // 'blog' hoặc 'event'
            'votable_id' => 'required|integer',
            'score' => 'required|integer|min:1|max:5',
        ]);

        $votableType = 'App\\Models\\' . ucfirst($request->votable_type);

        Vote::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'votable_type' => $votableType,
                'votable_id' => $request->votable_id,
            ],
            ['score' => $request->score]
        );

        return response()->json(['message' => 'Đánh giá thành công']);
    }

    public function average($type, $id)
    {
        $votableType = 'App\\Models\\' . ucfirst($type);

        $average = Vote::where('votable_type', $votableType)
            ->where('votable_id', $id)
            ->avg('score');

        return response()->json(['average' => round($average, 2)]);
    }
}
