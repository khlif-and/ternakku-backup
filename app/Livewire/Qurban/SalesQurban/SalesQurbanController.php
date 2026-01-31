<?php

namespace App\Http\Controllers\Admin\Qurban;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SalesQurbanController extends Controller
{
    public function index()
    {
        // Dummy data sementara
        $salesQurbans = [];

        return view('admin.qurban.sales_qurban.index', compact('salesQurbans'));
    }

    public function create()
    {
        return view('admin.qurban.sales_qurban.create');
    }
}
