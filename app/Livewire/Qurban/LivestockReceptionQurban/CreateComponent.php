<?php

namespace App\Livewire\Qurban\LivestockReceptionQurban;

use Livewire\Component;
use App\Models\Farm;
use App\Models\LivestockType;
use App\Models\LivestockBreed;
use App\Models\LivestockSex;
use App\Models\LivestockGroup;
use App\Models\LivestockClassification;
use App\Services\Web\Qurban\LivestockReceptionQurban\LivestockReceptionCoreService;
use Illuminate\Support\Facades\Log;

class CreateComponent extends Component
{
    public Farm $farm;

    public $transaction_date;
    public $supplier;
    public $eartag_number;
    public $rfid_number;
    public $livestock_type_id;
    public $livestock_group_id;
    public $livestock_breed_id;
    public $livestock_sex_id;
    public $livestock_classification_id;
    public $pen_id;
    public $age_years = 0;
    public $age_months = 0;
    public $weight;
    public $price_per_kg = 0;
    public $price_per_head = 0;
    public $qurban_price = 0;
    public $notes;
    public $characteristics;

    protected function rules()
    {
        return [
            'transaction_date' => 'required|date',
            'supplier' => 'nullable|string|max:255',
            'eartag_number' => 'required|string|max:255',
            'rfid_number' => 'nullable|string|max:255',
            'livestock_type_id' => 'required',
            'livestock_group_id' => 'required',
            'livestock_breed_id' => 'required',
            'livestock_sex_id' => 'required',
            'livestock_classification_id' => 'required',
            'pen_id' => 'required',
            'age_years' => 'required|integer|min:0',
            'age_months' => 'required|integer|min:0|max:11',
            'weight' => 'required|numeric|min:0|max:999999.99',
            'price_per_kg' => 'required|numeric|min:0|max:999999.99',
            'price_per_head' => 'required|numeric|min:0|max:999999999999.99',
            'qurban_price' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:255',
            'characteristics' => 'nullable|string|max:255',
        ];
    }

    protected $messages = [
        'transaction_date.required' => 'Tanggal penerimaan wajib diisi.',
        'eartag_number.required' => 'Nomor eartag wajib diisi.',
        'livestock_type_id.required' => 'Jenis ternak wajib dipilih.',
        'livestock_group_id.required' => 'Kelompok ternak wajib dipilih.',
        'livestock_breed_id.required' => 'Ras ternak wajib dipilih.',
        'livestock_sex_id.required' => 'Jenis kelamin wajib dipilih.',
        'livestock_classification_id.required' => 'Klasifikasi wajib dipilih.',
        'pen_id.required' => 'Kandang wajib dipilih.',
        'weight.required' => 'Berat wajib diisi.',
    ];

    public function mount(Farm $farm)
    {
        $this->farm = $farm;
        $this->transaction_date = now()->format('Y-m-d');
    }

    public function save(LivestockReceptionCoreService $coreService)
    {
        $this->validate();

        try {
            $reception = $coreService->store($this->farm, [
                'transaction_date' => $this->transaction_date,
                'supplier' => $this->supplier,
                'eartag_number' => $this->eartag_number,
                'rfid_number' => $this->rfid_number,
                'livestock_type_id' => $this->livestock_type_id,
                'livestock_group_id' => $this->livestock_group_id,
                'livestock_breed_id' => $this->livestock_breed_id,
                'livestock_sex_id' => $this->livestock_sex_id,
                'livestock_classification_id' => $this->livestock_classification_id,
                'pen_id' => $this->pen_id,
                'age_years' => $this->age_years,
                'age_months' => $this->age_months,
                'weight' => $this->weight,
                'price_per_kg' => $this->price_per_kg,
                'price_per_head' => $this->price_per_head,
                'qurban_price' => $this->qurban_price,
                'notes' => $this->notes,
                'characteristics' => $this->characteristics,
            ]);

            session()->flash('success', 'Penerimaan ternak qurban berhasil ditambahkan.');
            return redirect()->route('qurban.livestock-reception.show', $reception->id);

        } catch (\Throwable $e) {
            Log::error('Qurban Reception Create Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.qurban.livestock-reception.create-component', [
            'livestockTypes' => LivestockType::all(),
            'livestockBreeds' => LivestockBreed::all(),
            'livestockSexes' => LivestockSex::all(),
            'livestockGroups' => LivestockGroup::all(),
            'livestockClassifications' => LivestockClassification::all(),
            'pens' => $this->farm->pens ?? collect(),
        ]);
    }
}
