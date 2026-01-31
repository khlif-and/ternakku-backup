<?php

namespace App\Http\Controllers\Admin\Qurban;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class QurbanDeliveryOrderDataController extends Controller
{
public function index()
{
    // Dummy data atau dari model nanti
    $qurbanDeliveryOrderData = [];

    return view('admin.qurban.qurbanDeliveryOrderData.index', compact('qurbanDeliveryOrderData'));
}


    public function create()
    {
        return view('admin.qurban.qurbanDeliveryOrderData.create');
    }
}
