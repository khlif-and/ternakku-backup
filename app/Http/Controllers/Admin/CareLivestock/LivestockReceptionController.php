<?php

namespace App\Http\Controllers\Admin\CareLivestock;

use App\Models\Livestock;
use Illuminate\Support\Facades\DB;
use App\Models\LivestockReceptionH;
use App\Models\LivestockReceptionD;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use App\Enums\LivestockStatusEnum;
use App\Http\Requests\Farming\LivestockReceptionStoreRequest;
use App\Enums\LivestockTypeEnum;
use App\Enums\LivestockSexEnum;
use App\Enums\LivestockGroupEnum;
use App\Enums\LivestockClassificationEnum;

class LivestockReceptionController extends Controller
{
public function index($farmId)
{
    $farm = request()->attributes->get('farm');
    if (!$farm) {
        abort(404, 'Farm tidak ditemukan');
    }

    $search = request('search');
    $perPage = request('per_page', 10);

    $receptions = LivestockReceptionD::with([
            'livestockReceptionH',
            'livestockType',
            'livestockBreed',
            'livestockSex',
            'pen',
        ])
        ->whereHas('livestockReceptionH', function ($query) use ($farm) {
            $query->where('farm_id', $farm->id);
        })
        ->when($search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('eartag_number', 'like', "%{$search}%")
                  ->orWhere('rfid_number', 'like', "%{$search}%")
                  ->orWhereHas('livestockType', function ($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('livestockBreed', function ($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        })
        ->latest()
        ->paginate($perPage)
        ->appends(request()->query()); // Pertahankan query string saat paginate

    $livestocks = Livestock::with([
            'livestockType',
            'livestockBreed',
            'livestockClassification',
            'pen',
        ])
        ->where('farm_id', $farm->id)
        ->latest()
        ->get();

    return view('admin.care_livestock.livestock_reception.index', compact('farm', 'receptions', 'livestocks'));
}




    public function create($farmId)
    {
        $farm = request()->attributes->get('farm');
        if (!$farm)
            abort(404, 'Farm tidak ditemukan');

        $farm->load('pens');

        $livestockTypes = DB::table('livestock_types')->pluck('name', 'id');
        $sexes = DB::table('livestock_sexes')->pluck('name', 'id');
        $groups = DB::table('livestock_groups')->pluck('name', 'id');
        $classifications = DB::table('livestock_classifications')->pluck('name', 'id');

$mode = 'create';

return view('admin.care_livestock.livestock_reception.create', compact(
    'farm',
    'livestockTypes',
    'sexes',
    'groups',
    'classifications',
    'mode'
));

    }

    public function store(LivestockReceptionStoreRequest $request, $farmId)
    {
        $farm = request()->attributes->get('farm');
        if (!$farm)
            abort(404, 'Farm tidak ditemukan');

        $validated = $request->validated();

        try {
            DB::beginTransaction();

            $livestockReceptionH = LivestockReceptionH::create([
                'farm_id' => $farm->id,
                'transaction_date' => $validated['transaction_date'],
                'supplier' => $validated['supplier'] ?? '',
                'notes' => $validated['notes'] ?? null,
            ]);

            $receptionData = [
                'eartag_number' => $validated['eartag_number'],
                'rfid_number' => $validated['rfid_number'] ?? null,
                'livestock_type_id' => $validated['livestock_type_id'],
                'livestock_group_id' => $validated['livestock_group_id'],
                'livestock_breed_id' => $validated['livestock_breed_id'],
                'livestock_sex_id' => $validated['livestock_sex_id'],
                'livestock_classification_id' => $validated['livestock_classification_id'],
                'pen_id' => $validated['pen_id'],
                'age_years' => $validated['age_years'],
                'age_months' => $validated['age_months'],
                'weight' => $validated['weight'],
                'price_per_kg' => $validated['price_per_kg'],
                'price_per_head' => $validated['price_per_head'],
                'notes' => $validated['notes'] ?? null,
                'characteristics' => $validated['characteristics'] ?? null,
            ];

            if ($request->hasFile('photo')) {
                $file = $validated['photo'];
                $fileName = time() . '-' . $file->getClientOriginalName();
                $file->storeAs('receptions', $fileName, 'public');
                $receptionData['photo'] = 'storage/receptions/' . $fileName;
            }

            $reception = new LivestockReceptionD($receptionData);
            $reception->livestockReceptionH()->associate($livestockReceptionH);
            $reception->save();

            $livestock = Livestock::create([
                'farm_id' => $farm->id,
                'livestock_reception_d_id' => $reception->id,
                'livestock_status_id' => LivestockStatusEnum::HIDUP->value,
                'eartag_number' => $reception->eartag_number,
                'rfid_number' => $reception->rfid_number,
                'livestock_type_id' => $reception->livestock_type_id,
                'livestock_group_id' => $reception->livestock_group_id,
                'livestock_breed_id' => $reception->livestock_breed_id,
                'livestock_sex_id' => $reception->livestock_sex_id,
                'livestock_classification_id' => $reception->livestock_classification_id,
                'pen_id' => $reception->pen_id,
                'start_age_years' => $reception->age_years,
                'start_age_months' => $reception->age_months,
                'last_weight' => $reception->weight,
                'photo' => $reception->photo ?? null,
                'characteristics' => $reception->characteristics ?? null,
            ]);

            $phenotypeData = collect($validated)->only([
                'height',
                'body_length',
                'hip_height',
                'hip_width',
                'chest_width',
                'head_length',
                'head_width',
                'ear_length',
                'body_weight'
            ])->filter();

            if ($phenotypeData->isNotEmpty()) {
                $livestock->livestockPhenotype()->create($phenotypeData->toArray());
            }

            DB::commit();

            return redirect()
                ->route('admin.care-livestock.livestock-reception.index', $farm->id)
                ->with('success', 'Registrasi ternak berhasil ditambahkan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Gagal registrasi ternak (web):', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan data ternak. Silakan coba lagi.');
        }
    }

    public function show($farmId, $id)
{
    $farm = request()->attributes->get('farm');
    if (!$farm) abort(404, 'Farm tidak ditemukan');

    $reception = LivestockReceptionD::with('livestockReceptionH')->whereHas('livestockReceptionH', function ($q) use ($farm) {
        $q->where('farm_id', $farm->id);
    })->findOrFail($id);

    return view('admin.care_livestock.livestock_reception.show', compact('farm', 'reception'));
}

public function edit($farmId, $id)
{
    $farm = request()->attributes->get('farm');
    if (!$farm) abort(404, 'Farm tidak ditemukan');

    $reception = LivestockReceptionD::with('livestockReceptionH')->whereHas('livestockReceptionH', function ($q) use ($farm) {
        $q->where('farm_id', $farm->id);
    })->findOrFail($id);

    $farm->load('pens');

    $livestockTypes = DB::table('livestock_types')->pluck('name', 'id');
    $sexes = DB::table('livestock_sexes')->pluck('name', 'id');
    $groups = DB::table('livestock_groups')->pluck('name', 'id');
    $classifications = DB::table('livestock_classifications')->pluck('name', 'id');

    return view('admin.care_livestock.livestock_reception.edit', compact(
        'farm',
        'reception',
        'livestockTypes',
        'sexes',
        'groups',
        'classifications'
    ));
}

public function update(LivestockReceptionStoreRequest $request, $farmId, $id)
{
    $farm = request()->attributes->get('farm');
    if (!$farm) abort(404, 'Farm tidak ditemukan');

    $validated = $request->validated();

    $reception = LivestockReceptionD::whereHas('livestockReceptionH', function ($q) use ($farm) {
        $q->where('farm_id', $farm->id);
    })->findOrFail($id);

    try {
        DB::beginTransaction();

        $reception->livestockReceptionH->update([
            'transaction_date' => $validated['transaction_date'],
            'supplier' => $validated['supplier'] ?? '',
            'notes' => $validated['notes'] ?? null,
        ]);

        $receptionData = [
            'eartag_number' => $validated['eartag_number'],
            'rfid_number' => $validated['rfid_number'] ?? null,
            'livestock_type_id' => $validated['livestock_type_id'],
            'livestock_group_id' => $validated['livestock_group_id'],
            'livestock_breed_id' => $validated['livestock_breed_id'],
            'livestock_sex_id' => $validated['livestock_sex_id'],
            'livestock_classification_id' => $validated['livestock_classification_id'],
            'pen_id' => $validated['pen_id'],
            'age_years' => $validated['age_years'],
            'age_months' => $validated['age_months'],
            'weight' => $validated['weight'],
            'price_per_kg' => $validated['price_per_kg'],
            'price_per_head' => $validated['price_per_head'],
            'notes' => $validated['notes'] ?? null,
            'characteristics' => $validated['characteristics'] ?? null,
        ];

        if ($request->hasFile('photo')) {
            if ($reception->photo && file_exists(public_path($reception->photo))) {
                unlink(public_path($reception->photo));
            }
            $file = $validated['photo'];
            $fileName = time() . '-' . $file->getClientOriginalName();
            $file->storeAs('receptions', $fileName, 'public');
            $receptionData['photo'] = 'storage/receptions/' . $fileName;
        }

        $reception->update($receptionData);

        $livestock = $reception->livestock;
        $livestock->update([
            'livestock_status_id' => LivestockStatusEnum::HIDUP->value,
            'eartag_number' => $reception->eartag_number,
            'rfid_number' => $reception->rfid_number,
            'livestock_type_id' => $reception->livestock_type_id,
            'livestock_group_id' => $reception->livestock_group_id,
            'livestock_breed_id' => $reception->livestock_breed_id,
            'livestock_sex_id' => $reception->livestock_sex_id,
            'livestock_classification_id' => $reception->livestock_classification_id,
            'pen_id' => $reception->pen_id,
            'start_age_years' => $reception->age_years,
            'start_age_months' => $reception->age_months,
            'last_weight' => $reception->weight,
            'photo' => $reception->photo,
            'characteristics' => $reception->characteristics,
        ]);

        $phenotypeData = collect($validated)->only([
            'height', 'body_length', 'hip_height', 'hip_width',
            'chest_width', 'head_length', 'head_width', 'ear_length', 'body_weight'
        ])->filter();

        if ($phenotypeData->isNotEmpty()) {
            $livestock->livestockPhenotype()->updateOrCreate([], $phenotypeData->toArray());
        }

        DB::commit();

        return redirect()
            ->route('admin.care-livestock.livestock-reception.index', $farm->id)
            ->with('success', 'Data registrasi ternak berhasil diperbarui.');
    } catch (\Throwable $e) {
        DB::rollBack();
        Log::error('Update gagal (web): ' . $e->getMessage());
        return redirect()->back()->with('error', 'Gagal memperbarui data. Silakan coba lagi.');
    }
}

public function destroy($farmId, $id)
{
    $farm = request()->attributes->get('farm');
    if (!$farm) abort(404, 'Farm tidak ditemukan');

    try {
        DB::beginTransaction();

        $reception = LivestockReceptionD::whereHas('livestockReceptionH', function ($q) use ($farm) {
            $q->where('farm_id', $farm->id);
        })->findOrFail($id);

        if ($reception->photo && file_exists(public_path($reception->photo))) {
            unlink(public_path($reception->photo));
        }

        $header = $reception->livestockReceptionH;

        $reception->delete();

        if ($header->livestockReceptionD()->count() === 0) {
            $header->delete();
        }

        DB::commit();

        return redirect()
            ->route('admin.care-livestock.livestock-reception.index', $farm->id)
            ->with('success', 'Registrasi ternak berhasil dihapus.');
    } catch (\Throwable $e) {
        DB::rollBack();
        Log::error('Destroy gagal (web): ' . $e->getMessage());
        return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus data.');
    }
}


}
