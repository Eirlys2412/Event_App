<?php

namespace App\Modules\Tuongtac\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;    
class TuongtacController extends Controller
{
    public function index()
    {
        return view('Tuongtac::index');
    }
    public function toggle(Request $request)
{
    $request->validate([
        'reactionable_id' => 'required|integer',
        'reactionable_type' => 'required|string',
        'type' => 'nullable|string', // default là 'like'
    ]);

    $type = $request->type ?? 'like';

    $reaction = Like::where([
        'user_id' => auth()->id(),
        'likeable_id' => $request->likeable_id,
        'likeable_type' => $request->likeable_type,
    ])->first();

    if ($reaction) {
        if ($reaction->type === $type) {
            $reaction->delete(); // toggle off
            return response()->json(['message' => 'Reaction removed']);
        } else {
            $reaction->update(['type' => $type]); // update reaction
            return response()->json(['message' => 'Reaction updated']);
        }
    }

    Like::create([
        'user_id' => auth()->id(),
        'likeable_id' => $request->likeable_id,
        'likeable_type' => $request->likeable_type,
        'type' => $type,
    ]);

    return response()->json(['message' => 'Reaction added']);
}

}