<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;

class SalesOrderController extends Controller
{
    public function index()
    {
        return view('marketing.sales-order.index');
    }

    public function create()
    {
        return view('marketing.sales-order.create');
    }

    public function show($id)
    {
        return view('marketing.sales-order.show', compact('id'));
    }
}
