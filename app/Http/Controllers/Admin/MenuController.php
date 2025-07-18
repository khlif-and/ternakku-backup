<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pen;
use App\Models\MilkProductionGlobal;
use Illuminate\Support\Facades\Auth;

class MenuController extends Controller
{
    public function index()
    {
        $farmId = session('selected_farm');
        if (!$farmId) {
            return redirect()->route('care_livestock');
        }

        // Query contoh aktivitas terbaru
        $recentPens = Pen::where('farm_id', $farmId)
            ->latest('created_at')->take(5)->get()
            ->map(function ($x) {
                return [
                    'type' => 'pen',
                    'description' => "Pen baru <span class='font-semibold text-slate-900'>{$x->name}</span> ditambahkan.",
                    'created_at' => $x->created_at,
                ];
            });

        $recentMilks = MilkProductionGlobal::where('farm_id', $farmId)
            ->latest('transaction_date')->take(5)->get()
            ->map(function ($x) {
                return [
                    'type' => 'milk',
                    'description' => "Produksi susu <span class='font-semibold text-slate-900'>{$x->quantity_liters} L</span> dicatat.",
                    'created_at' => $x->transaction_date,
                ];
            });

        // Merge dan sort by tanggal terbaru
        $recentActivities = collect()
            ->merge($recentPens)
            ->merge($recentMilks)
            ->sortByDesc('created_at')
            ->take(5)
            ->values();

        return view('menu.index', compact('recentActivities'));
    }
}
