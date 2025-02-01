<?php

namespace App\Http\Controllers\Admin\Qurban;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function dashboard()
    {
        return view('layouts.qurban.index');
    }
}
