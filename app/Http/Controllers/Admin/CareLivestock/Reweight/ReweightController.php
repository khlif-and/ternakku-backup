<?php

namespace App\Http\Controllers\Admin\CareLivestock\Reweight;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Farm;

class ReweightController extends Controller
{
    public function index(Request $request, $farmId)
    {
        $farm = Farm::findOrFail($farmId);
        return view('admin.care_livestock.reweight.index', compact('farm'));
    }

    public function create($farmId)
    {
        $farm = Farm::findOrFail($farmId);
        return view('admin.care_livestock.reweight.create', compact('farm'));
    }

    public function store(Request $request, $farmId)
    {
         $farm = Farm::findOrFail($farmId);
         // Logic handled by Livewire
         return redirect()->route('admin.care-livestock.reweight.index', $farm->id);
    }

    public function show($farmId, $id)
    {
        $farm = Farm::findOrFail($farmId);
        return view('admin.care_livestock.reweight.show', compact('farm', 'id'));
    }

    public function edit($farmId, $id)
    {
        $farm = Farm::findOrFail($farmId);
        return view('admin.care_livestock.reweight.edit', compact('farm', 'id'));
    }

    public function update(Request $request, $farmId, $id)
    {
        $farm = Farm::findOrFail($farmId);

        // Logic handled by Livewire
        return redirect()->route('admin.care-livestock.reweight.index', $farm->id);
    }

    public function destroy($farmId, $id)
    {
        $farm = Farm::findOrFail($farmId);
        // Logic handled by Livewire
        return redirect()->route('admin.care-livestock.reweight.index', $farm->id);
    }
}
