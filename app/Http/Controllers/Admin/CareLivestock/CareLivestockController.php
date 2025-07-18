<?php

namespace App\Http\Controllers\Admin\CareLivestock;

use App\Http\Controllers\Controller;
use App\Models\Farm;
use App\Models\MilkAnalysisGlobal;
use App\Models\MilkProductionGlobal;
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

        $analysisData = MilkAnalysisGlobal::where('farm_id', $farm->id)
            ->orderBy('transaction_date')
            ->get();

        $milkProductionData = MilkProductionGlobal::where('farm_id', $farm->id)
            ->orderBy('transaction_date')
            ->get();


        $recentSales = \App\Models\LivestockSaleWeightD::whereHas('livestockSaleWeightH', function($q) use ($farm_id) {
                $q->where('farm_id', $farm_id);
            })
            ->latest('created_at')->take(5)->get()
            ->map(function($x){
                return [
                    'type' => 'sale',
                    'description' => "Penjualan <span class='font-semibold text-slate-900'>{$x->weight} kg</span> berhasil dicatat.",
                    'created_at' => $x->created_at,
                ];
            });

        $recentMilk = $milkProductionData->sortByDesc('transaction_date')->take(5)->map(function($x){
            return [
                'type' => 'milk_production',
                'description' => "Input produksi susu <span class='font-semibold text-slate-900'>{$x->quantity_liters} L</span> oleh <span class='font-semibold text-slate-900'>{$x->milker_name}</span>.",
                'created_at' => $x->transaction_date,
            ];
        });

        $recentAnalysis = $analysisData->sortByDesc('transaction_date')->take(3)->map(function($x){
            return [
                'type' => 'milk_analysis',
                'description' => "Analisis susu <span class='font-semibold text-slate-900'>{$x->bj}</span> BJ pada <span class='font-semibold text-slate-900'>" . \Carbon\Carbon::parse($x->transaction_date)->format('d M Y') . "</span>.",
                'created_at' => $x->transaction_date,
            ];
        });

        $recentActivities = collect()
            ->merge($recentSales)
            ->merge($recentMilk)
            ->merge($recentAnalysis)
            ->sortByDesc('created_at')
            ->take(10)
            ->values();

        return view('admin.care_livestock.dashboard', compact(
            'farm',
            'pens',
            'livestocks',
            'maleCount',
            'femaleCount',
            'typeCounts',
            'classificationCounts',
            'analysisData',
            'milkProductionData',
            'recentActivities'
        ));
    }
}
