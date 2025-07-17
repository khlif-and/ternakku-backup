<?php

namespace App\Http\Controllers\Admin\CareLivestock;

use App\Http\Controllers\Controller;
use App\Models\Farm;
use Illuminate\Support\Str;

class CareLivestockController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $existingFarm = Farm::where('owner_id', $user->id)->first();
        if (!$existingFarm) {
            return redirect()->route('farm.create');
        }

        session(['selected_farm' => $existingFarm->id]);

        return redirect()->route('admin.care-livestock.dashboard', [
            'farm_id' => $existingFarm->id,
        ]);
    }

public function dashboard($farm_id)
{
    $farm = Farm::with('pens')->findOrFail($farm_id);
    $pens = $farm->pens;

    $livestocks = \App\Models\LivestockReceptionD::with([
        'livestockType',
        'livestockBreed',
        'livestockClassification',
        'livestockSex',
        'pen',
        'livestockReceptionH',
    ])
        ->whereHas('livestockReceptionH', fn ($q) => $q->where('farm_id', $farm->id))
        ->latest()
        ->get();

    $maleCount = $livestocks->where(fn ($item) => Str::lower($item->livestockSex?->name) === 'jantan')->count();
    $femaleCount = $livestocks->where(fn ($item) => Str::lower($item->livestockSex?->name) === 'betina')->count();

    $typeCounts = $livestocks
        ->groupBy(fn ($item) => $item->livestockType->name ?? 'Tidak diketahui')
        ->map(fn ($group) => $group->count());

    $classificationCounts = $livestocks
        ->groupBy(fn ($item) => $item->livestockClassification->name ?? 'Tidak diketahui')
        ->map(fn ($group) => $group->count());

    return view('admin.care_livestock.dashboard', compact(
        'farm',
        'pens',
        'livestocks',
        'maleCount',
        'femaleCount',
        'typeCounts',
        'classificationCounts'
    ));
}

}
