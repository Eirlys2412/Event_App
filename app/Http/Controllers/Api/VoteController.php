<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Vote;

class VoteController extends Controller
{
    // POST /api/v1/votes
    public function store(Request $request)
    {
        $data = $request->validate([
            'votable_type' => 'required|string',
            'votable_id'   => 'required|integer',
            'rating'       => 'required|integer|min:1|max:5',
        ]);
        $data['user_id'] = Auth::id();

        // Create or update vote
        Vote::updateOrCreate(
            [
                'user_id' => $data['user_id'],
                'votable_type' => $data['votable_type'],
                'votable_id' => $data['votable_id'],
            ],
            ['rating' => $data['rating']]
        );

        // Calculate average
        $avg = Vote::where('votable_type', $data['votable_type'])
            ->where('votable_id', $data['votable_id'])
            ->avg('rating');

        return response()->json(['average' => round($avg,1)], 200);
    }

    // GET /api/v1/votes/average?votable_type=&votable_id=
    public function average(Request $request)
    {
        $data = $request->validate([
            'votable_type' => 'required|string',
            'votable_id'   => 'required|integer',
        ]);
        $avg = Vote::where('votable_type', $data['votable_type'])
            ->where('votable_id', $data['votable_id'])
            ->avg('rating');
        return response()->json(['average' => round($avg,1)], 200);
    }
} 