<?php

namespace App\Http\Controllers\Admin\Qurban;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class QurbanDeliveryOrderDataController extends Controller
{
    public function index()
    {
        // Dummy data sementara
        $qurbanDeliveryOrders = [];

        return view('admin.qurban.qurban_delivery_order_data.index', compact('qurbanDeliveryOrders'));
    }

    public function create()
    {
        return view('admin.qurban.qurban_delivery_order_data.create');
    }
}
