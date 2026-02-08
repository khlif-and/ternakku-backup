<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('marketing.dashboard.index');
    }
}
