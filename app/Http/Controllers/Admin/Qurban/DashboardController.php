<?php

namespace App\Http\Controllers\Admin\Qurban;

use App\Models\Farm;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $farmId = session('selected_farm');
        $farm = Farm::find($farmId);
        return view('admin.qurban.dashboard' , compact('farm'));
    }
}
