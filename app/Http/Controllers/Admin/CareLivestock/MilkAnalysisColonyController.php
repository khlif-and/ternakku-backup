<?php

namespace App\Http\Controllers\Admin\CareLivestock;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\MilkAnalysisColonyD;
use App\Models\MilkAnalysisH;
use App\Models\MilkAnalysisColonyLivestock;
use App\Http\Requests\Farming\MilkAnalysisColonyStoreRequest;
use App\Http\Requests\Farming\MilkAnalysisColonyUpdateRequest;

class MilkAnalysisColonyController extends Controller
{
    public function index($farmId, Request $request)
    {
        $farm = $request->attributes->get('farm');

        $milkAnalysisColony = MilkAnalysisColonyD::whereHas('milkAnalysisH', function ($query) use ($farm, $request) {
            $query->where('farm_id', $farm->id)->where('type', 'colony');

            if ($request->filled('start_date')) {
                $query->where('transaction_date', '>=', $request->input('start_date'));
            }
            if ($request->filled('end_date')) {
                $query->where('transaction_date', '<=', $request->input('end_date'));
            }
        });

        if ($request->filled('pen_id')) {
            $milkAnalysisColony->where('pen_id', $request->input('pen_id'));
        }

        $data = $milkAnalysisColony->orderByDesc('id')->paginate(15);

        return view('admin.care_livestock.milk_analysis_colony.index', [
            'data' => $data,
            'farm' => $farm,
            'request' => $request,
        ]);
    }

    public function create($farmId, Request $request)
    {
        $farm = $request->attributes->get('farm');
        // Kamu bisa load relasi lain di sini (misal pen, dsb)
        return view('admin.care_livestock.milk_analysis_colony.create', [
            'farm' => $farm,
            // 'pens' => $farm->pens, dst...
        ]);
    }

    public function store(MilkAnalysisColonyStoreRequest $request, $farmId)
    {
        $farm = $request->attributes->get('farm');
        $validated = $request->validated();

        $pen = $farm->pens()->find($validated['pen_id']);
        if (!$pen) {
            return back()->withInput()->with('error', 'Pen tidak ditemukan.');
        }
        $livestockLactations = $pen->livestockLactations();
        $totalLivestockLactations = count($livestockLactations);

        if ($totalLivestockLactations < 1) {
            return back()->withInput()->with('error', 'Tidak ada ternak laktasi pada kandang ini.');
        }

        try {
            DB::beginTransaction();

            $milkAnalysisH = MilkAnalysisH::create([
                'farm_id'          => $farm->id,
                'transaction_date' => $validated['transaction_date'],
                'type'             => 'colony',
                'notes'            => $validated['notes'],
            ]);

            $milkAnalysisColonyD = MilkAnalysisColonyD::create([
                'milk_analysis_h_id' => $milkAnalysisH->id,
                'pen_id' => $validated['pen_id'],
                'bj' => $validated['bj'] ?? null,
                'at' => $validated['at'] ?? null,
                'ab' => $validated['ab'] ?? null,
                'mbrt' => $validated['mbrt'] ?? null,
                'a_water' => $validated['a_water'] ?? null,
                'protein' => $validated['protein'] ?? null,
                'fat' => $validated['fat'] ?? null,
                'snf' => $validated['snf'] ?? null,
                'ts' => $validated['ts'] ?? null,
                'rzn' => $validated['rzn'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'total_livestock' => $totalLivestockLactations,
            ]);

            foreach($livestockLactations as $livestock){
                MilkAnalysisColonyLivestock::create([
                    'milk_analysis_colony_d_id' => $milkAnalysisColonyD->id,
                    'livestock_id' => $livestock->id
                ]);
            }

            DB::commit();

            return redirect()
                ->route('admin.care-livestock.milk-analysis-colony.index', $farm->id)
                ->with('success', 'Data analisa koloni berhasil disimpan.');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    public function show($farmId, $id, Request $request)
    {
        $farm = $request->attributes->get('farm');
        $data = MilkAnalysisColonyD::whereHas('milkAnalysisH', function ($query) use ($farm) {
            $query->where('farm_id', $farm->id)->where('type', 'colony');
        })->findOrFail($id);

        return view('admin.care_livestock.milk_analysis_colony.show', [
            'data' => $data,
            'farm' => $farm,
        ]);
    }

    public function edit($farmId, $id, Request $request)
    {
        $farm = $request->attributes->get('farm');
        $data = MilkAnalysisColonyD::whereHas('milkAnalysisH', function ($query) use ($farm) {
            $query->where('farm_id', $farm->id)->where('type', 'colony');
        })->findOrFail($id);

        return view('admin.care_livestock.milk_analysis_colony.edit', [
            'data' => $data,
            'farm' => $farm,
        ]);
    }

    public function update(MilkAnalysisColonyUpdateRequest $request, $farmId, $id)
    {
        $farm = $request->attributes->get('farm');
        $validated = $request->validated();

        $milkAnalysisColonyD = MilkAnalysisColonyD::whereHas('milkAnalysisH', function ($query) use ($farm) {
            $query->where('farm_id', $farm->id)->where('type', 'colony');
        })->findOrFail($id);

        try {
            DB::beginTransaction();

            $milkAnalysisH = $milkAnalysisColonyD->milkAnalysisH;
            $milkAnalysisH->update([
                'transaction_date' => $validated['transaction_date'],
                'notes'            => $validated['notes'] ?? null,
            ]);

            $milkAnalysisColonyD->update([
                'bj' => $validated['bj'] ?? null,
                'at' => $validated['at'] ?? null,
                'ab' => $validated['ab'] ?? null,
                'mbrt' => $validated['mbrt'] ?? null,
                'a_water' => $validated['a_water'] ?? null,
                'protein' => $validated['protein'] ?? null,
                'fat' => $validated['fat'] ?? null,
                'snf' => $validated['snf'] ?? null,
                'ts' => $validated['ts'] ?? null,
                'rzn' => $validated['rzn'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);

            DB::commit();

            return redirect()
                ->route('admin.care-livestock.milk-analysis-colony.index', $farm->id)
                ->with('success', 'Data analisa koloni berhasil diupdate.');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan saat mengupdate data.');
        }
    }

    public function destroy($farmId, $id, Request $request)
    {
        $farm = $request->attributes->get('farm');
        $milkAnalysisColonyD = MilkAnalysisColonyD::whereHas('milkAnalysisH', function ($query) use ($farm) {
            $query->where('farm_id', $farm->id)->where('type', 'colony');
        })->findOrFail($id);

        try {
            DB::beginTransaction();

            MilkAnalysisColonyLivestock::where('milk_analysis_colony_d_id', $milkAnalysisColonyD->id)->delete();
            $milkAnalysisColonyD->delete();

            $milkAnalysisH = $milkAnalysisColonyD->milkAnalysisH;
            if ($milkAnalysisH && !$milkAnalysisH->milkAnalysisColonyD()->exists()) {
                $milkAnalysisH->delete();
            }

            DB::commit();

            return redirect()
                ->route('admin.care-livestock.milk-analysis-colony.index', $farm->id)
                ->with('success', 'Data berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Delete MilkAnalysisColony Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }
}
