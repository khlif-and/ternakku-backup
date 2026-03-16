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

    private function getFarm()
    {
        $farm = request()->attributes->get('farm');

        if (!$farm && session()->has('selected_farm')) {
            $farm = \App\Models\Farm::find(session('selected_farm'));
        }

        return $farm;
    }

    public function index(Request $request)
    {
        $farm = $this->getFarm();

        return view('admin.qurban.livestock_reception.index', compact('farm'));
    }

    public function create()
    {
        $farm = $this->getFarm();
        $farm->load('pens');

        return view('admin.qurban.livestock_reception.create', compact('farm'));
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

        try {
            $farm = $this->getFarm();
            $reception = $this->service->store($farm, $validated);

            return redirect()->route('qurban.livestock-reception.show', $reception->id)
                ->with('success', 'Penerimaan ternak qurban berhasil ditambahkan.');
        } catch (\Throwable $e) {
            report($e);
            return redirect()->route('qurban.livestock-reception.create')
                ->with('error', 'Gagal menambahkan penerimaan ternak qurban.');
        }
    }

    public function show($id)
    {
        $farm = $this->getFarm();
        $reception = $this->service->find($farm, $id);

        return view('admin.qurban.livestock_reception.show', compact('farm', 'reception'));
    }

    public function edit($id)
    {
        $farm = $this->getFarm();
        $farm->load('pens');
        $reception = $this->service->find($farm, $id);

        return view('admin.qurban.livestock_reception.edit', compact('farm', 'reception'));
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

        try {
            $farm = $this->getFarm();
            $this->service->update($farm, $id, $validated);

            return redirect()->route('qurban.livestock-reception.show', $id)
                ->with('success', 'Penerimaan ternak qurban berhasil diperbarui.');
        } catch (\Throwable $e) {
            report($e);
            return redirect()->route('qurban.livestock-reception.edit', $id)
                ->with('error', 'Gagal memperbarui penerimaan ternak qurban.');
        }
    }

    public function destroy($id)
    {
        try {
            $farm = $this->getFarm();
            $this->service->delete($farm, $id);

            return redirect()->route('qurban.livestock-reception.index')
                ->with('success', 'Penerimaan ternak qurban berhasil dihapus.');
        } catch (\Throwable $e) {
            report($e);
            return redirect()->route('qurban.livestock-reception.index')
                ->with('error', 'Gagal menghapus penerimaan ternak qurban.');
        }
    }
}