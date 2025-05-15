<?php

namespace App\Modules\Blog\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function upload(Request $request)
    {
        echo 'test function upload';
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('resources', 'public');
            return response()->json([
                'status' => 'true',
                'link' => '/storage/' . $path
            ]);
        }
        return response()->json(['status' => 'false']);
    }
}