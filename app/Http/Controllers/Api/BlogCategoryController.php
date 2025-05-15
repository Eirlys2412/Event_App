<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\Request;

class BlogCategoryController extends Controller
{
    public function index()
    {
        return BlogCategory::all();
    }

    public function show($id)
    {
        return BlogCategory::findOrFail($id);
    }
    
}
