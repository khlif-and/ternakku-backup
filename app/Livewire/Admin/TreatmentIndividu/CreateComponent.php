<?php

namespace App\Livewire\Admin\TreatmentIndividu;

use Livewire\Component;
use App\Models\Farm;
use App\Models\Disease;
use App\Services\Web\Farming\TreatmentIndividu\TreatmentIndividuCoreService;
use Illuminate\Support\Facades\Log;

class CreateComponent extends Component
{
    public Farm $farm;

    public $transaction_date;
    public $livestock_id;
    public $disease_id;
    public $notes;
    public $medicines = [];
    public $treatments = [];

    public $livestocks = [];
    public $diseases = [];

    protected function rules()
    {
        return [
            'transaction_date' => 'required|date',
            'livestock_id' => 'required|exists:livestocks,id',
            'disease_id' => 'required|exists:diseases,id',
            'notes' => 'nullable|string',
            'medicines' => 'array',
            'medicines.*.name' => 'required_with:medicines.*.unit|string',
            'medicines.*.unit' => 'required_with:medicines.*.name|string',
            'medicines.*.qty_per_unit' => 'required_with:medicines.*.name|numeric|min:0',
            'medicines.*.price_per_unit' => 'required_with:medicines.*.name|numeric|min:0',
            'treatments' => 'array',
            'treatments.*.name' => 'required_with:treatments.*.cost|string',
            'treatments.*.cost' => 'required_with:treatments.*.name|numeric|min:0',
        ];
    }

    protected $messages = [
        'transaction_date.required' => 'Tanggal wajib diisi.',
        'livestock_id.required' => 'Ternak wajib dipilih.',
        'disease_id.required' => 'Penyakit wajib dipilih.',
    ];

    public function mount(Farm $farm)
    {
        $this->farm = $farm;
        $this->transaction_date = now()->format('Y-m-d');
        $this->livestocks = $farm->livestocks()->get();
        $this->diseases = Disease::all();
        
        $this->addMedicine();
        $this->addAction();
    }

    public function addMedicine()
    {
        $this->medicines[] = [
            'name' => '',
            'unit' => '',
            'qty_per_unit' => 0,
            'price_per_unit' => 0,
        ];
    }

    public function removeMedicine($index)
    {
        if (count($this->medicines) > 1) {
            unset($this->medicines[$index]);
            $this->medicines = array_values($this->medicines);
        }
    }

    public function addAction()
    {
        $this->treatments[] = [
            'name' => '',
            'cost' => 0,
        ];
    }

    public function removeAction($index)
    {
        if (count($this->treatments) > 1) {
            unset($this->treatments[$index]);
            $this->treatments = array_values($this->treatments);
        }
    }

    public function save(TreatmentIndividuCoreService $coreService)
    {
        $this->validate();

        try {
            $treatmentIndividuD = $coreService->store($this->farm, [
                'transaction_date' => $this->transaction_date,
                'livestock_id' => $this->livestock_id,
                'disease_id' => $this->disease_id,
                'notes' => $this->notes,
                'medicines' => $this->medicines,
                'treatments' => $this->treatments,
            ]);

            session()->flash('success', 'Data treatment individu berhasil ditambahkan.');
            return redirect()->route('admin.care-livestock.treatment-individu.show', [$this->farm->id, $treatmentIndividuD->id]);
        } catch (\Throwable $e) {
            Log::error('TreatmentIndividu Create Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.treatment-individu.create-component');
    }
}