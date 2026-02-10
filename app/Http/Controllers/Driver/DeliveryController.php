<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;

class DeliveryController extends Controller
{
    public function index()
    {
        return view('driver.delivery.index');
    }

    public function show($id)
    {
        return view('driver.delivery.show', compact('id'));
    }
}
