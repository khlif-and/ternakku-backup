<?php

namespace App\Http\Controllers\Admin\Qurban;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        return view('admin.qurban.customer.index');
    }
}
