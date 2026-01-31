<?php

namespace App\Http\Controllers\Admin\Qurban;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CancelationQurbanController extends Controller
{
    public function index()
    {
        $cancelationQurbans = []; // Data masih kosong sesuai web route

        return view('admin.qurban.cancelation_qurban.index', compact('cancelationQurbans'));
    }

    public function create()
    {
        return view('admin.qurban.cancelation_qurban.create');
    }
}
