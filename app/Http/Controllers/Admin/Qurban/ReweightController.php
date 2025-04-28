<?php

namespace App\Http\Controllers\Admin\Qurban;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReweightController extends Controller
{
    public function index()
    {
        // Untuk sementara, dummy data dulu
        $reweights = [];

        return view('admin.qurban.reweight.index', compact('reweights'));
    }

    public function create()
    {
        return view('admin.qurban.reweight.create');
    }
}
