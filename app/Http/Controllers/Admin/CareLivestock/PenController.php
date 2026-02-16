<?php

namespace App\Http\Controllers\Admin\CareLivestock;

use App\Models\Farm;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PenController extends Controller
{
    public function index($farmId)
    {
        $farm = Farm::findOrFail($farmId);
        return view('admin.care_livestock.pens.index', compact('farm'));
    }

    public function create($farmId)
    {
        $farm = Farm::findOrFail($farmId);
        return view('admin.care_livestock.pens.create', compact('farm'));
    }

    public function show($farmId, $penId)
    {
        $farm = Farm::findOrFail($farmId);
        return view('admin.care_livestock.pens.show', compact('farm', 'penId'));
    }

    public function edit($farmId, $penId)
    {
        $farm = Farm::findOrFail($farmId);
        return view('admin.care_livestock.pens.edit', compact('farm', 'penId'));
    }
}
