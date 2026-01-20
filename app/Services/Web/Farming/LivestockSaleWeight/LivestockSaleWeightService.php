<?php

namespace App\Services\Web\Farming\LivestockSaleWeight;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Livestock;
use App\Models\LivestockSaleWeightD;
use App\Enums\LivestockStatusEnum;
use App\Http\Requests\Farming\LivestockSaleWeightStoreRequest;
use App\Http\Requests\Farming\LivestockSaleWeightUpdateRequest;

class LivestockSaleWeightService
{
    protected LivestockSaleWeightCoreService $core;

    public function __construct(LivestockSaleWeightCoreService $core)
    {
        $this->core = $core;
    }

    public function index(Request $request)
    {
        $farm = $request->attributes->get('farm');
        $filters = $request->all(); // Pass all filters including request params
        
        $saleWeights = $this->core->listSaleWeights($farm, $filters);

        return view('admin.care_livestock.livestock_sale_weight.index', compact('saleWeights', 'farm'));
    }

    public function create(Request $request)
    {
        $farm = $request->attributes->get('farm');
        $livestockSaleWeight = Livestock::with(['livestockType', 'livestockBreed'])
            ->where('farm_id', $farm->id)
            ->where('livestock_status_id', LivestockStatusEnum::HIDUP->value)
            ->get();

        return view('admin.care_livestock.livestock_sale_weight.create', compact('farm', 'livestockSaleWeight'));
    }

    public function store(LivestockSaleWeightStoreRequest $request)
    {
        DB::beginTransaction();

        try {
            $farm = request()->attributes->get('farm');
            $data = $request->validated();
            $photo = $request->file('photo'); // Handle explicit photo file check

            $this->core->storeSaleWeight($farm, $data, $photo);

            DB::commit();
            return redirect()->route('admin.care-livestock.livestock-sale-weight.index', $farm->id)
                ->with('success', 'Data berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            // Check if exception message is user-friendly (like business validation)
            if ($e->getMessage() === 'Ternak tidak ditemukan atau sudah tidak hidup.') {
                 return redirect()->back()->withErrors(['livestock_id' => $e->getMessage()]);
            }
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data.']);
        }
    }

    public function show($id)
    {
        $farm = request()->attributes->get('farm');
        $saleWeight = $this->core->findSaleWeight($farm, $id);

        return view('admin.care_livestock.livestock_sale_weight.show', compact('saleWeight', 'farm'));
    }

    public function edit($id)
    {
        $farm = request()->attributes->get('farm');
        $saleWeight = $this->core->findSaleWeight($farm, $id);

        $livestockSaleWeight = Livestock::where('farm_id', $farm->id)
            ->where(function ($q) use ($saleWeight) {
                $q->where('livestock_status_id', LivestockStatusEnum::HIDUP->value)
                  ->orWhere('id', $saleWeight->livestock_id);
            })
            ->get();

        return view('admin.care_livestock.livestock_sale_weight.edit', compact('saleWeight', 'farm', 'livestockSaleWeight'));
    }

    public function update(LivestockSaleWeightUpdateRequest $request, $id)
    {
        DB::beginTransaction();

        try {
            $farm = request()->attributes->get('farm');
            $data = $request->validated();
            $photo = $request->file('photo');

            $this->core->updateSaleWeight($farm, $id, $data, $photo);

            DB::commit();
            return redirect()->route('admin.care-livestock.livestock-sale-weight.index', $farm->id)
                ->with('success', 'Data berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
             if (str_contains($e->getMessage(), 'Ternak baru tidak ditemukan')) {
                 return redirect()->back()->withErrors(['livestock_id' => $e->getMessage()]);
            }
            return redirect()->back()->withErrors(['error' => 'Gagal memperbarui data.']);
        }
    }

    public function destroy($id)
    {
        $farm = request()->attributes->get('farm');

        return DB::transaction(function () use ($id, $farm) {
            $this->core->deleteSaleWeight($farm, $id);

            return redirect()->route('admin.care-livestock.livestock-sale-weight.index', $farm->id)
                ->with('success', 'Data berhasil dihapus.');
        });
    }
}
