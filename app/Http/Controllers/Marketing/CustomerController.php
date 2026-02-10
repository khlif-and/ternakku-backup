<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;

class CustomerController extends Controller
{
    public function index()
    {
        return view('marketing.customer.index');
    }

    public function create()
    {
        return view('marketing.customer.create');
    }

    public function show($id)
    {
        return view('marketing.customer.show', compact('id'));
    }
}
