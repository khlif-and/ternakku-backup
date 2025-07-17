<?php

namespace App\Http\Controllers\Admin\CareLivestock;

use App\Models\Livestock;
use Illuminate\Http\Request;
use App\Enums\LivestockStatusEnum;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\LivestockSaleWeightD;
use App\Models\LivestockSaleWeightH;
use App\Http\Requests\Farming\LivestockSaleWeightStoreRequest;
use App\Http\Requests\Farming\LivestockSaleWeightUpdateRequest;

class LivestockSaleWeightController extends Controller
{
    public function index(Request $request)
    {
        $farm = request()->attributes->get('farm');

        $saleWeights = LivestockSaleWeightD::with(['livestock', 'livestockSaleWeightH'])
            ->whereHas('livestock')
            ->whereHas('livestockSaleWeightH', function ($query) use ($farm, $request) {
                $query->where('farm_id', $farm->id);

                if ($request->filled('start_date')) {
                    $query->where('transaction_date', '>=', $request->input('start_date'));
                }
                if ($request->filled('end_date')) {
                    $query->where('transaction_date', '<=', $request->input('end_date'));
                }
            });

        if ($request->filled('livestock_type_id')) {
            $saleWeights->whereHas('livestock', fn($q) => $q->where('livestock_type_id', $request->livestock_type_id));
        }
        if ($request->filled('livestock_group_id')) {
            $saleWeights->whereHas('livestock', fn($q) => $q->where('livestock_group_id', $request->livestock_group_id));
        }
        if ($request->filled('livestock_breed_id')) {
            $saleWeights->whereHas('livestock', fn($q) => $q->where('livestock_breed_id', $request->livestock_breed_id));
        }
        if ($request->filled('livestock_sex_id')) {
            $saleWeights->whereHas('livestock', fn($q) => $q->where('livestock_sex_id', $request->livestock_sex_id));
        }
        if ($request->filled('pen_id')) {
            $saleWeights->whereHas('livestock', fn($q) => $q->where('pen_id', $request->pen_id));
        }
        if ($request->filled('customer')) {
            $saleWeights->whereHas('livestockSaleWeightH', fn($q) => $q->where('customer', 'like', '%' . $request->customer . '%'));
        }

        $saleWeights = $saleWeights->orderByDesc('id')->paginate(10);

        return view('admin.care_livestock.livestock_sale_weight.index', compact('saleWeights', 'farm'));
    }

    public function create(Request $request)
    {
        $farm = $request->attributes->get('farm');
        // HANYA ternak HIDUP yang tampil di dropdown
        $livestockSaleWeight = Livestock::with(['livestockType', 'livestockBreed'])
            ->where('farm_id', $farm->id)
            ->where('livestock_status_id', LivestockStatusEnum::HIDUP->value)
            ->get();

        return view('admin.care_livestock.livestock_sale_weight.create', compact('farm', 'livestockSaleWeight'));
    }

    public function store(LivestockSaleWeightStoreRequest $request)
    {
        $validated = $request->validated();
        $farm = request()->attributes->get('farm');

        DB::beginTransaction();

        try {
            $livestock = Livestock::find($validated['livestock_id']);

            // CEK STATUS HIDUP
            if (!$livestock || $livestock->livestock_status_id !== LivestockStatusEnum::HIDUP->value) {
                return redirect()->back()->withErrors(['livestock_id' => 'Ternak tidak ditemukan atau sudah tidak hidup.']);
            }

            $header = LivestockSaleWeightH::create([
                'farm_id' => $farm->id,
                'transaction_date' => $validated['transaction_date'],
                'customer' => $validated['customer'],
                'notes' => $validated['notes'] ?? null,
            ]);

            $detailData = $validated;
            $detailData['livestock_sale_weight_h_id'] = $header->id;
            unset($detailData['customer'], $detailData['transaction_date']);

            if (isset($validated['photo']) && $request->hasFile('photo')) {
                $file = $validated['photo'];
                $fileName = time() . '-' . $file->getClientOriginalName();
                $filePath = 'sale_weights/';
                $detailData['photo'] = uploadNeoObject($file, $fileName, $filePath);
            }

            LivestockSaleWeightD::create($detailData);

            // SET TERJUAL
            $livestock->update(['livestock_status_id' => LivestockStatusEnum::TERJUAL->value]);

            DB::commit();
            return redirect()->route('admin.care-livestock.livestock-sale-weight.index', $farm->id)
                ->with('success', 'Data berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data.']);
        }
    }

    public function show($id)
    {
        $farm = request()->attributes->get('farm');

        $saleWeight = LivestockSaleWeightD::whereHas('livestockSaleWeightH', fn($q) => $q->where('farm_id', $farm->id))->findOrFail($id);

        return view('admin.care_livestock.livestock_sale_weight.show', compact('saleWeight', 'farm'));
    }

    public function edit($id)
    {
        $farm = request()->attributes->get('farm');

        $saleWeight = LivestockSaleWeightD::whereHas('livestockSaleWeightH', fn($q) => $q->where('farm_id', $farm->id))->findOrFail($id);

        // KASIH PILIHAN TERNak yang masih hidup atau memang TERNak ini (biar edit bisa, meski status sudah TERJUAL)
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
            $validated = $request->validated();

            $saleWeight = LivestockSaleWeightD::whereHas('livestockSaleWeightH', fn($q) => $q->where('farm_id', $farm->id))->findOrFail($id);
            $header = $saleWeight->livestockSaleWeightH;

            $oldLivestockId = $saleWeight->livestock_id;

            // Kalau ganti ternak, pastikan yg baru status HIDUP
            if ($validated['livestock_id'] != $oldLivestockId) {
                $newLivestock = Livestock::find($validated['livestock_id']);
                if (!$newLivestock || $newLivestock->livestock_status_id !== LivestockStatusEnum::HIDUP->value) {
                    return redirect()->back()->withErrors(['livestock_id' => 'Ternak baru tidak ditemukan atau sudah tidak hidup.']);
                }
            }

            $header->update([
                'transaction_date' => $validated['transaction_date'],
                'customer' => $validated['customer'],
                'notes' => $validated['notes'] ?? null,
            ]);

            $saleWeight->update([
                'livestock_id' => $validated['livestock_id'],
                'weight' => $validated['weight'],
                'price_per_kg' => $validated['price_per_kg'],
                'price_per_head' => $validated['price_per_head'],
                'notes' => $validated['notes'] ?? $saleWeight->notes,
            ]);

            if (isset($validated['photo']) && $request->hasFile('photo')) {
                if ($saleWeight->photo)
                    deleteNeoObject($saleWeight->photo);

                $file = $validated['photo'];
                $fileName = time() . '-' . $file->getClientOriginalName();
                $filePath = 'livestock_sales/';
                $saleWeight->photo = uploadNeoObject($file, $fileName, $filePath);
                $saleWeight->save();
            }

            // Update status lama
            if ($oldLivestockId !== $validated['livestock_id']) {
                $old = Livestock::find($oldLivestockId);
                if ($old && $old->livestock_status_id === LivestockStatusEnum::TERJUAL->value) {
                    $old->update(['livestock_status_id' => LivestockStatusEnum::HIDUP->value]);
                }

                $new = Livestock::find($validated['livestock_id']);
                if ($new && $new->livestock_status_id !== LivestockStatusEnum::TERJUAL->value) {
                    $new->update(['livestock_status_id' => LivestockStatusEnum::TERJUAL->value]);
                }
            }

            DB::commit();
            return redirect()->route('admin.care-livestock.livestock-sale-weight.index', $farm->id)
                ->with('success', 'Data berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Gagal memperbarui data.']);
        }
    }

    public function destroy($id)
    {
        $farm = request()->attributes->get('farm');

        return DB::transaction(function () use ($id, $farm) {
            $saleWeight = LivestockSaleWeightD::whereHas('livestockSaleWeightH', fn($q) => $q->where('farm_id', $farm->id))->findOrFail($id);

            if ($saleWeight->photo)
                deleteNeoObject($saleWeight->photo);

            $livestock = Livestock::find($saleWeight->livestock_id);
            if ($livestock && $livestock->livestock_status_id === LivestockStatusEnum::TERJUAL->value) {
                $livestock->update(['livestock_status_id' => LivestockStatusEnum::HIDUP->value]);
            }

            $header = $saleWeight->livestockSaleWeightH;
            $saleWeight->delete();

            if ($header->livestockSaleWeightD()->count() === 0) {
                $header->delete();
            }

            return redirect()->route('admin.care-livestock.livestock-sale-weight.index', $farm->id)
                ->with('success', 'Data berhasil dihapus.');
        });
    }
}
