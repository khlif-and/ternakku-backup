<?php

namespace App\Http\Controllers\Admin\Qurban;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LiveestockDeliveryNoteController extends Controller
{
    public function index()
    {
        // Untuk sementara, dummy data dulu
        $livestocks = [];

        return view('admin.qurban.liveestockDeliveryNote.index', compact('payments'));
    }

    public function create()
    {
        return view('admin.qurban.liveestockDeliveryNote.create');
    }
}
