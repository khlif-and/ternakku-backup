<?php

namespace App\Http\Controllers\Admin\CareLivestock;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\MilkProductionGlobal;
use App\Http\Requests\Farming\MilkProductionGlobalStoreRequest;
use App\Http\Requests\Farming\MilkProductionGlobalUpdateRequest;

class MilkProductionGlobalController extends Controller
{
    public function index($farmId, Request $request)
    {
        $farm = $request->attributes->get('farm');
        $milkProductionGlobal = MilkProductionGlobal::where('farm_id', $farm->id);

        if ($request->filled('start_date')) {
            $milkProductionGlobal->where('transaction_date', '>=', $request->input('start_date'));
        }

        if ($request->filled('end_date')) {
            $milkProductionGlobal->where('transaction_date', '<=', $request->input('end_date'));
        }

        $data = $milkProductionGlobal->get();

        return view('admin.care_livestock.milk_production_global.index', [
            'data' => $data,
            'farm' => $farm,
            'request' => $request,
        ]);
    }

    public function create($farmId, Request $request)
    {
        $farm = $request->attributes->get('farm');
        return view('admin.care_livestock.milk_production_global.create', [
            'farm' => $farm,
        ]);
    }

    public function store(MilkProductionGlobalStoreRequest $request, $farmId)
    {
        DB::beginTransaction();

        try {
            $farm = $request->attributes->get('farm');
            $validated = $request->validated();

            $milkProductionGlobal = MilkProductionGlobal::create([
                'farm_id' => $farm->id,
                'transaction_date' => $validated['transaction_date'],
                'milking_shift' => $validated['milking_shift'],
                'milking_time' => $validated['milking_time'],
                'milker_name' => $validated['milker_name'],
                'quantity_liters' => $validated['quantity_liters'],
                'milk_condition' => $validated['milk_condition'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);

            DB::commit();

            return redirect()
                ->route('admin.care-livestock.milk-production-global.index', $farm->id)
                ->with('success', 'Data produksi susu berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', $e->getMessage() . ' | LINE: ' . $e->getLine());
        }
    }

    public function show($farmId, $id, Request $request)
    {
        try {
            $farm = $request->attributes->get('farm');
            $data = MilkProductionGlobal::where('farm_id', $farm->id)->findOrFail($id);

            return view('admin.care_livestock.milk_production_global.show', [
                'data' => $data,
                'farm' => $farm,
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Data tidak ditemukan.');
        }
    }

    public function edit($farmId, $id, Request $request)
    {
        try {
            $farm = $request->attributes->get('farm');
            $data = MilkProductionGlobal::where('farm_id', $farm->id)->findOrFail($id);

            return view('admin.care_livestock.milk_production_global.edit', [
                'data' => $data,
                'farm' => $farm,
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Data tidak ditemukan.');
        }
    }

    public function update(MilkProductionGlobalUpdateRequest $request, $farmId, $id)
    {
        DB::beginTransaction();

        try {
            $farm = $request->attributes->get('farm');
            $validated = $request->validated();

            $data = MilkProductionGlobal::where('farm_id', $farm->id)->findOrFail($id);

            $data->update([
                'transaction_date' => $validated['transaction_date'],
                'milking_shift' => $validated['milking_shift'],
                'milking_time' => $validated['milking_time'],
                'milker_name' => $validated['milker_name'],
                'quantity_liters' => $validated['quantity_liters'],
                'milk_condition' => $validated['milk_condition'] ?? null,
                'notes' => $validated['notes'] ?? null
            ]);

            DB::commit();

            return redirect()
                ->route('admin.care-livestock.milk-production-global.index', $farm->id)
                ->with('success', 'Data berhasil diupdate.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal update data. ' . $e->getMessage());
        }
    }

    public function destroy($farmId, $id, Request $request)
    {
        DB::beginTransaction();

        try {
            $farm = $request->attributes->get('farm');
            $data = MilkProductionGlobal::where('farm_id', $farm->id)->findOrFail($id);

            $data->delete();

            DB::commit();

            return redirect()
                ->route('admin.care-livestock.milk-production-global.index', $farm->id)
                ->with('success', 'Data berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus data.');
        }
    }
}
