<?php

namespace App\Modules\MOMOPAY\Controllers;

use App\Http\Controllers\Controller;

class MOMOPAYController extends Controller
{
    public function index()
    {
        return view('MOMOPAY::index');
    }
}