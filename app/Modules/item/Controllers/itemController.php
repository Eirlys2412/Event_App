<?php

namespace App\Modules\item\Controllers;

use App\Http\Controllers\Controller;

class itemController extends Controller
{
    public function index()
    {
        return view('item::index');
    }
}