<?php

namespace App\Http\Controllers\Admin\Qurban\LivestockReceptionQurban;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Web\Qurban\LivestockReceptionQurban\LivestockReceptionService;

class LivestockReceptionQurbanController extends Controller
{
    protected LivestockReceptionService $service;

    public function __construct(LivestockReceptionService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        return $this->service->index($request);
    }

    public function create()
    {
        return $this->service->create();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'transaction_date' => 'required|date',
            'supplier' => 'nullable|string',
            'eartag_number' => 'required|string|max:255',
            'rfid_number' => 'nullable|string|max:255',
            'livestock_type_id' => 'required|exists:livestock_types,id',
            'livestock_group_id' => 'required|exists:livestock_groups,id',
            'livestock_breed_id' => 'required|exists:livestock_breeds,id',
            'livestock_sex_id' => 'required|exists:livestock_sexes,id',
            'livestock_classification_id' => 'required|exists:livestock_classifications,id',
            'pen_id' => 'required|exists:pens,id',
            'age_years' => 'required|integer|min:0',
            'age_months' => 'required|integer|min:0|max:11',
            'weight' => 'required|numeric|min:0|max:999999.99',
            'price_per_kg' => 'required|numeric|min:0|max:999999.99',
            'price_per_head' => 'required|numeric|min:0|max:999999999999.99',
            'notes' => 'nullable|string|max:255',
            'characteristics' => 'nullable|string|max:255',
            'qurban_price' => 'nullable|numeric|min:0',
        ]);

        return $this->service->store($validated);
    }

    public function show($id)
    {
        return $this->service->show($id);
    }

    public function edit($id)
    {
        return $this->service->edit($id);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'transaction_date' => 'required|date',
            'supplier' => 'nullable|string',
            'eartag_number' => 'required|string|max:255',
            'rfid_number' => 'nullable|string|max:255',
            'livestock_type_id' => 'required|exists:livestock_types,id',
            'livestock_group_id' => 'required|exists:livestock_groups,id',
            'livestock_breed_id' => 'required|exists:livestock_breeds,id',
            'livestock_sex_id' => 'required|exists:livestock_sexes,id',
            'livestock_classification_id' => 'required|exists:livestock_classifications,id',
            'pen_id' => 'required|exists:pens,id',
            'age_years' => 'nullable|integer|min:0',
            'age_months' => 'nullable|integer|min:0|max:11',
            'weight' => 'required|numeric|min:0|max:999999.99',
            'price_per_kg' => 'required|numeric|min:0|max:999999.99',
            'price_per_head' => 'required|numeric|min:0|max:999999999999.99',
            'notes' => 'nullable|string|max:255',
            'characteristics' => 'nullable|string|max:255',
            'qurban_price' => 'nullable|numeric|min:0',
        ]);

        return $this->service->update($id, $validated);
    }

    public function destroy($id)
    {
        return $this->service->destroy($id);
    }
}
