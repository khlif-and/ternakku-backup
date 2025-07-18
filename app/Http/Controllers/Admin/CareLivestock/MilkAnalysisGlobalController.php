<?php

namespace App\Http\Controllers\Admin\CareLivestock;

use App\Http\Controllers\Controller;
use App\Models\MilkAnalysisGlobal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Farming\MilkAnalysisGlobalStoreRequest;
use App\Http\Requests\Farming\MilkAnalysisGlobalUpdateRequest;

class MilkAnalysisGlobalController extends Controller
{
    public function index($farmId, Request $request)
    {
        $farm = $request->attributes->get('farm');

        $query = MilkAnalysisGlobal::where('farm_id', $farm->id);

        if ($request->filled('start_date')) {
            $query->where('transaction_date', '>=', $request->input('start_date'));
        }
        if ($request->filled('end_date')) {
            $query->where('transaction_date', '<=', $request->input('end_date'));
        }

        $data = $query->orderByDesc('transaction_date')->paginate(20);

        return view('admin.care_livestock.milk_analysis_global.index', [
            'farm' => $farm,
            'data' => $data,
        ]);
    }

    public function create($farmId, Request $request)
    {
        $farm = $request->attributes->get('farm');
        return view('admin.care_livestock.milk_analysis_global.create', [
            'farm' => $farm,
        ]);
    }

    public function store(MilkAnalysisGlobalStoreRequest $request, $farmId)
    {
        $farm = $request->attributes->get('farm');
        $validated = $request->validated();

        try {
            DB::beginTransaction();

            $record = MilkAnalysisGlobal::create([
                'farm_id'          => $farm->id,
                'transaction_date' => $validated['transaction_date'],
                'bj'               => $validated['bj'] ?? null,
                'at'               => $validated['at'] ?? null,
                'ab'               => $validated['ab'] ?? null,
                'mbrt'             => $validated['mbrt'] ?? null,
                'a_water'          => $validated['a_water'] ?? null,
                'protein'          => $validated['protein'] ?? null,
                'fat'              => $validated['fat'] ?? null,
                'snf'              => $validated['snf'] ?? null,
                'ts'               => $validated['ts'] ?? null,
                'rzn'              => $validated['rzn'] ?? null,
                'notes'            => $validated['notes'] ?? null,
            ]);

            DB::commit();

            return redirect()
                ->route('admin.care-livestock.milk-analysis-global.index', $farm->id)
                ->with('success', 'Data berhasil disimpan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    public function show($farmId, $id, Request $request)
    {
        $farm = $request->attributes->get('farm');
        $item = MilkAnalysisGlobal::where('farm_id', $farm->id)->findOrFail($id);

        return view('admin.care_livestock.milk_analysis_global.show', [
            'farm' => $farm,
            'item' => $item,
        ]);
    }

    public function edit($farmId, $id, Request $request)
    {
        $farm = $request->attributes->get('farm');
        $item = MilkAnalysisGlobal::where('farm_id', $farm->id)->findOrFail($id);

        return view('admin.care_livestock.milk_analysis_global.edit', [
            'farm' => $farm,
            'item' => $item,
        ]);
    }

    public function update(MilkAnalysisGlobalUpdateRequest $request, $farmId, $id)
    {
        $farm = $request->attributes->get('farm');
        $validated = $request->validated();

        try {
            DB::beginTransaction();

            $item = MilkAnalysisGlobal::where('farm_id', $farm->id)->findOrFail($id);
            $item->update([
                'transaction_date' => $validated['transaction_date'],
                'bj'               => $validated['bj'] ?? null,
                'at'               => $validated['at'] ?? null,
                'ab'               => $validated['ab'] ?? null,
                'mbrt'             => $validated['mbrt'] ?? null,
                'a_water'          => $validated['a_water'] ?? null,
                'protein'          => $validated['protein'] ?? null,
                'fat'              => $validated['fat'] ?? null,
                'snf'              => $validated['snf'] ?? null,
                'ts'               => $validated['ts'] ?? null,
                'rzn'              => $validated['rzn'] ?? null,
                'notes'            => $validated['notes'] ?? null,
            ]);

            DB::commit();

            return redirect()
                ->route('admin.care-livestock.milk-analysis-global.index', $farm->id)
                ->with('success', 'Data berhasil diupdate.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan saat update data.');
        }
    }

    public function destroy($farmId, $id, Request $request)
    {
        $farm = $request->attributes->get('farm');
        try {
            DB::beginTransaction();

            $item = MilkAnalysisGlobal::where('farm_id', $farm->id)->findOrFail($id);
            $item->delete();

            DB::commit();

            return redirect()
                ->route('admin.care-livestock.milk-analysis-global.index', $farm->id)
                ->with('success', 'Data berhasil dihapus.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }
}
