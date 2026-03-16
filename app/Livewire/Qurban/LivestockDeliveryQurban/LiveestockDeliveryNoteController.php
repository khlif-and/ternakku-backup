<?php

namespace App\Http\Controllers\Admin\Qurban;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LiveestockDeliveryNoteController extends Controller
{
    public function index()
    {
        $livestocks = [];

        return view('admin.qurban.liveestockDeliveryNote.index', compact('livestocks'));
    }

    public function create()
    {
        return view('admin.qurban.liveestockDeliveryNote.create');
    }
}
