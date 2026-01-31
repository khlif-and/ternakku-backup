<?php

namespace App\Http\Controllers\Admin\Qurban;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class QurbanDeliveryController extends Controller
{
    public function index()
    {
        $deliveries = [];

        return view('admin.qurban.qurban_delivery.index', compact('deliveries'));
    }

    public function create()
    {
        return view('admin.qurban.qurban_delivery.create');
    }
}
