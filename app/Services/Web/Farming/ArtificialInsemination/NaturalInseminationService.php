<?php

namespace App\Services\Web\Farming\ArtificialInsemination;

use App\Models\LivestockBreed;
use App\Enums\LivestockSexEnum;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Http\Requests\Farming\NaturalInseminationStoreRequest;
use App\Http\Requests\Farming\NaturalInseminationUpdateRequest;
use App\Models\InseminationNatural;

class NaturalInseminationService
{
    protected NaturalInseminationCoreService $core;

    public function __construct(NaturalInseminationCoreService $core)
    {
        $this->core = $core;
    }

    public function index($farmId, Request $request)
    {
        $farm = request()->attributes->get('farm');

        $items = InseminationNatural::with(['insemination','reproductionCycle.livestock'])
            ->whereHas('insemination', function ($query) use ($farm, $request) {
                $query->where('farm_id', $farm->id)
                      ->whereRaw('LOWER(type) = ?', ['natural']);

                if ($request->filled('start_date')) {
                    $query->where('transaction_date', '>=', $request->input('start_date'));
                }
                if ($request->filled('end_date')) {
                    $query->where('transaction_date', '<=', $request->input('end_date'));
                }
            })
            ->latest('id')
            ->get();

        return view('admin.care_livestock.natural_insemination.index', [
            'farm'    => $farm,
            'items'   => $items,
            'filters' => $request->only(['start_date','end_date']),
        ]);
    }

    public function create($farmId)
    {
        $farm = request()->attributes->get('farm');

        $livestocks = $farm->livestocks()
            ->where('livestock_sex_id', LivestockSexEnum::BETINA->value)
            ->get();

        $breeds = LivestockBreed::orderBy('name')->get(['id','name','livestock_type_id']);
        $breedsJson = $breeds->map(fn($b) => [
            'id'       => $b->id,
            'name'     => $b->name,
            'type_id'  => $b->livestock_type_id,
        ]);

        return view('admin.care_livestock.natural_insemination.create', compact('farm','livestocks','breeds','breedsJson'));
    }

    public function store(NaturalInseminationStoreRequest $request, $farmId)
    {
        $farm = request()->attributes->get('farm');
        $validated = $request->validated();

        try {
            $this->core->recordNatural($farm, $validated);
            return redirect()
                ->route('admin.care-livestock.natural-inseminasi.index', ['farm_id' => $farmId])
                ->with('success', 'Data created successfully');
        } catch (\InvalidArgumentException $e) {
            return back()->withInput()->withErrors(['sire_breed_id' => $e->getMessage()]);
        } catch (\Throwable $e) {
            Log::error('❌ Natural Insemination Store Error', ['message' => $e->getMessage()]);
            return back()->withInput()->with('error', 'An unexpected error occurred.');
        }
    }

    public function show($farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        $item = $this->core->findByFarm($farm, $id);

        return view('admin.care_livestock.natural_insemination.show', compact('farm','item'));
    }

    public function edit($farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        $item = $this->core->findByFarm($farm, $id);

        $livestocks = $farm->livestocks()
            ->where('livestock_sex_id', LivestockSexEnum::BETINA->value)
            ->get();

        $typeId = optional($item->reproductionCycle->livestock)->livestock_type_id;
        $breeds = LivestockBreed::when($typeId, fn($q) => $q->where('livestock_type_id', $typeId))
            ->orderBy('name')
            ->get(['id','name','livestock_type_id']);

        return view('admin.care_livestock.natural_insemination.edit', compact('farm','item','livestocks','breeds'));
    }

    public function update(NaturalInseminationUpdateRequest $request, $farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        $validated = $request->validated();

        try {
            $this->core->updateNatural($farm, $id, $validated);
            return redirect()
                ->route('admin.care-livestock.natural-inseminasi.index', ['farm_id' => $farmId])
                ->with('success', 'Data updated successfully');
        } catch (\InvalidArgumentException $e) {
            return back()->withInput()->withErrors(['sire_breed_id' => $e->getMessage()]);
        } catch (\Throwable $e) {
            Log::error('❌ Natural Insemination Update Error', ['message' => $e->getMessage()]);
            return back()->withInput()->with('error', 'An unexpected error occurred.');
        }
    }

    public function destroy($farmId, $id)
    {
        $farm = request()->attributes->get('farm');

        try {
            $this->core->deleteNatural($farm, $id);
            return redirect()
                ->route('admin.care-livestock.natural-inseminasi.index', ['farm_id' => $farmId])
                ->with('success', 'Data deleted successfully');
        } catch (\Throwable $e) {
            Log::error('❌ Natural Insemination Delete Error', ['message' => $e->getMessage()]);
            return back()->with('error', 'Error while deleting data.');
        }
    }
}
