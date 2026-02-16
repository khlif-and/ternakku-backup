<?php

namespace App\Http\Controllers\Admin\CareLivestock\FarmUser;

use App\Models\Farm;
use App\Http\Controllers\Controller;

class FarmUserController extends Controller
{
    public function index($farmId)
    {
        $farm = Farm::findOrFail($farmId);
        return view('admin.care_livestock.farm_users.index', compact('farm'));
    }

    public function create($farmId)
    {
        $farm = Farm::findOrFail($farmId);
        return view('admin.care_livestock.farm_users.create', compact('farm'));
    }

    public function show($farmId, $id)
    {
        $farm = Farm::findOrFail($farmId);
        return view('admin.care_livestock.farm_users.show', compact('farm', 'id'));
    }

    public function edit($farmId, $id)
    {
        $farm = Farm::findOrFail($farmId);
        return view('admin.care_livestock.farm_users.edit', compact('farm', 'id'));
    }
}
