<?php

namespace App\Http\Controllers\Admin\CareLivestock;

use App\Models\Livestock;
use App\Models\LivestockDeath;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Enums\LivestockStatusEnum;
use App\Http\Requests\Farming\LivestockDeathStoreRequest;
use App\Http\Requests\Farming\LivestockDeathUpdateRequest;

class LivestockDeathController extends Controller
{
    public function index($farmId, Request $request)
    {
        $farm = request()->attributes->get('farm');
        if (!$farm) abort(404, 'Farm tidak ditemukan');

        $deaths = LivestockDeath::with(['livestock'])
            ->where('farm_id', $farm->id);

        // Filter
        if ($request->filled('start_date')) {
            $deaths->where('transaction_date', '>=', $request->input('start_date'));
        }
        if ($request->filled('end_date')) {
            $deaths->where('transaction_date', '<=', $request->input('end_date'));
        }
        if ($request->filled('livestock_type_id')) {
            $deaths->whereHas('livestock', function ($q) use ($request) {
                $q->where('livestock_type_id', $request->livestock_type_id);
            });
        }
        if ($request->filled('livestock_group_id')) {
            $deaths->whereHas('livestock', function ($q) use ($request) {
                $q->where('livestock_group_id', $request->livestock_group_id);
            });
        }
        if ($request->filled('livestock_breed_id')) {
            $deaths->whereHas('livestock', function ($q) use ($request) {
                $q->where('livestock_breed_id', $request->livestock_breed_id);
            });
        }
        if ($request->filled('livestock_sex_id')) {
            $deaths->whereHas('livestock', function ($q) use ($request) {
                $q->where('livestock_sex_id', $request->livestock_sex_id);
            });
        }
        if ($request->filled('pen_id')) {
            $deaths->whereHas('livestock', function ($q) use ($request) {
                $q->where('pen_id', $request->pen_id);
            });
        }

        $deaths = $deaths->orderByDesc('transaction_date')->paginate(10)->appends($request->query());

        return view('admin.care_livestock.livestock_death.index', compact('farm', 'deaths'));
    }

public function create($farmId)
{
    $farm = request()->attributes->get('farm');
    if (!$farm) abort(404, 'Farm tidak ditemukan');

    // Hanya ternak hidup yang bisa dipilih
    $livestocks = \App\Models\Livestock::where('farm_id', $farm->id)
        ->where('livestock_status_id', \App\Enums\LivestockStatusEnum::HIDUP->value)
        ->get();

    // Siapkan data penyakit, dsb jika butuh dropdown
    $diseases = DB::table('diseases')->pluck('name', 'id'); // Atur sesuai kebutuhan

    // CUMA RETURN SEKALI, setelah semua variable ADA
    return view('admin.care_livestock.livestock_death.create', compact('farm', 'livestocks', 'diseases'));
}


public function store(LivestockDeathStoreRequest $request, $farmId)
{
    $farm = request()->attributes->get('farm');
    if (!$farm) abort(404, 'Farm tidak ditemukan');

    DB::beginTransaction();

    try {
        $validated = $request->validated();
        $livestock = Livestock::find($validated['livestock_id']);

        if (!$livestock || $livestock->livestock_status_id !== LivestockStatusEnum::HIDUP->value) {
            return redirect()->back()->withErrors(['livestock_id' => 'Ternak tidak ditemukan atau sudah mati.']);
        }

        // Hapus otomatis semua data penjualan ternak ini (jika ada)
        \App\Models\LivestockSaleWeightD::where('livestock_id', $livestock->id)->delete();

        // Input data kematian ternak
        LivestockDeath::create([
            'farm_id'        => $farm->id,
            'transaction_date' => $validated['transaction_date'],
            'livestock_id'   => $validated['livestock_id'],
            'disease_id'     => $validated['disease_id'] ?? null,
            'indication'     => $validated['indication'] ?? null,
            'notes'          => $validated['notes'] ?? null,
        ]);

        $livestock->update(['livestock_status_id' => LivestockStatusEnum::MATI->value]);

        DB::commit();
        return redirect()->route('admin.care-livestock.livestock-death.index', $farm->id)
            ->with('success', 'Data kematian ternak berhasil disimpan. Semua data penjualan terkait telah dihapus otomatis.');
    } catch (\Throwable $e) {
        DB::rollBack();
        return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data.']);
    }
}


    public function show($farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        if (!$farm) abort(404, 'Farm tidak ditemukan');

        $death = LivestockDeath::with(['livestock'])->where('farm_id', $farm->id)->findOrFail($id);

        return view('admin.care_livestock.livestock_death.show', compact('farm', 'death'));
    }

    public function edit($farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        if (!$farm) abort(404, 'Farm tidak ditemukan');

        $death = LivestockDeath::with('livestock')->where('farm_id', $farm->id)->findOrFail($id);

        // Semua ternak (boleh juga hanya yang status HIDUP atau MATI saja)
        $livestocks = Livestock::where('farm_id', $farm->id)->get();

        $diseases = DB::table('diseases')->pluck('name', 'id');

        return view('admin.care_livestock.livestock_death.edit', compact('farm', 'death', 'livestocks', 'diseases'));
    }

    public function update(LivestockDeathUpdateRequest $request, $farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        if (!$farm) abort(404, 'Farm tidak ditemukan');

        DB::beginTransaction();

        try {
            $validated = $request->validated();
            $death = LivestockDeath::where('farm_id', $farm->id)->findOrFail($id);

            $oldLivestockId = $death->livestock_id;
            $death->update([
                'transaction_date' => $validated['transaction_date'],
                'livestock_id'     => $validated['livestock_id'],
                'disease_id'       => $validated['disease_id'] ?? null,
                'indication'       => $validated['indication'] ?? null,
                'notes'            => $validated['notes'] ?? null,
            ]);

            // Update status ternak jika id berubah
            if ($oldLivestockId && $oldLivestockId != $validated['livestock_id']) {
                $old = Livestock::find($oldLivestockId);
                if ($old && $old->livestock_status_id == LivestockStatusEnum::MATI->value) {
                    $old->update(['livestock_status_id' => LivestockStatusEnum::HIDUP->value]);
                }
            }

            $new = Livestock::find($validated['livestock_id']);
            if ($new && $new->livestock_status_id != LivestockStatusEnum::MATI->value) {
                $new->update(['livestock_status_id' => LivestockStatusEnum::MATI->value]);
            }

            DB::commit();
            return redirect()->route('admin.care-livestock.livestock-death.index', $farm->id)
                ->with('success', 'Data kematian ternak berhasil diupdate.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat update data.']);
        }
    }

    public function destroy($farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        if (!$farm) abort(404, 'Farm tidak ditemukan');

        DB::beginTransaction();
        try {
            $death = LivestockDeath::where('farm_id', $farm->id)->findOrFail($id);

            $livestock = Livestock::find($death->livestock_id);
            $death->delete();

            if ($livestock && $livestock->livestock_status_id == LivestockStatusEnum::MATI->value) {
                $livestock->update(['livestock_status_id' => LivestockStatusEnum::HIDUP->value]);
            }

            DB::commit();
            return redirect()->route('admin.care-livestock.livestock-death.index', $farm->id)
                ->with('success', 'Data kematian ternak berhasil dihapus.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Gagal menghapus data.']);
        }
    }
}
