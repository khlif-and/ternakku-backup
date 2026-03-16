<?php

namespace App\Http\Controllers\Admin\CareLivestock\LivestockSaleWeight;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Farming\LivestockSaleWeightStoreRequest;
use App\Http\Requests\Farming\LivestockSaleWeightUpdateRequest;
use App\Services\Web\Farming\LivestockSaleWeight\LivestockSaleWeightService;

class LivestockSaleWeightController extends Controller
{
    protected LivestockSaleWeightService $service;

    public function __construct(LivestockSaleWeightService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $farm = $request->attributes->get('farm');
        $saleWeights = $this->service->list($farm, $request->all());

        return view('admin.care_livestock.livestock_sale_weight.index', compact('saleWeights', 'farm'));
    }

    public function create(Request $request)
    {
        $farm = $request->attributes->get('farm');
        $livestockSaleWeight = $this->service->getAliveLivestocks($farm);

        return view('admin.care_livestock.livestock_sale_weight.create', compact('farm', 'livestockSaleWeight'));
    }

    public function store(LivestockSaleWeightStoreRequest $request)
    {
        $farm = request()->attributes->get('farm');

        DB::beginTransaction();
        try {
            $this->service->store($farm, $request->validated(), $request->file('photo'));

            DB::commit();
            return redirect()
                ->route('admin.care-livestock.livestock-sale-weight.index', $farm->id)
                ->with('success', 'Data berhasil ditambahkan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    public function show($id)
    {
        $farm = request()->attributes->get('farm');
        $saleWeight = $this->service->find($farm, $id);

        return view('admin.care_livestock.livestock_sale_weight.show', compact('saleWeight', 'farm'));
    }

    public function edit($id)
    {
        $farm = request()->attributes->get('farm');
        $saleWeight = $this->service->find($farm, $id);
        $livestockSaleWeight = $this->service->getEditLivestocks($farm, $saleWeight);

        return view('admin.care_livestock.livestock_sale_weight.edit', compact('saleWeight', 'farm', 'livestockSaleWeight'));
    }

    public function update(LivestockSaleWeightUpdateRequest $request, $id)
    {
        $farm = request()->attributes->get('farm');

        DB::beginTransaction();
        try {
            $this->service->update($farm, $id, $request->validated(), $request->file('photo'));

            DB::commit();
            return redirect()
                ->route('admin.care-livestock.livestock-sale-weight.index', $farm->id)
                ->with('success', 'Data berhasil diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat memperbarui data.');
        }
    }

    public function destroy($id)
    {
        $farm = request()->attributes->get('farm');

        try {
            DB::transaction(function () use ($farm, $id) {
                $this->service->delete($farm, $id);
            });

            return redirect()
                ->route('admin.care-livestock.livestock-sale-weight.index', $farm->id)
                ->with('success', 'Data berhasil dihapus.');
        } catch (\Throwable $e) {
            report($e);
            return back()->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }
}

