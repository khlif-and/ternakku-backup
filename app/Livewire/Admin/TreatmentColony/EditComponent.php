<?php

namespace App\Livewire\Admin\TreatmentColony;

use Livewire\Component;
use App\Models\Farm;
use App\Models\Disease;
use App\Models\TreatmentColonyD;
use App\Services\Web\Farming\TreatmentColony\TreatmentColonyCoreService;
use Illuminate\Support\Facades\Log;

class EditComponent extends Component
{
    public Farm $farm;
    public TreatmentColonyD $treatmentColony;

    public $transaction_date;
    public $pen_id;
    public $disease_id;
    public $notes;
    public $medicines = [];
    public $treatments = [];

    public $pens = [];
    public $diseases = [];

    protected function rules()
    {
        return [
            'transaction_date' => 'required|date',
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
        'disease_id.required' => 'Penyakit wajib dipilih.',
    ];

    public function mount(Farm $farm, TreatmentColonyD $treatmentColony)
    {
        $this->farm = $farm;
        $this->treatmentColony = $treatmentColony;
        $this->pens = $farm->pens;
        $this->diseases = Disease::all();
        $this->fillFormData();
    }

    public function fillFormData()
    {
        $this->transaction_date = $this->treatmentColony->treatmentH?->transaction_date;
        $this->pen_id = $this->treatmentColony->pen_id;
        $this->disease_id = $this->treatmentColony->disease_id;
        $this->notes = $this->treatmentColony->notes;

        $this->medicines = $this->treatmentColony->treatmentColonyMedicineItems->map(function ($item) {
            return [
                'name' => $item->name,
                'unit' => $item->unit,
                'qty_per_unit' => $item->qty_per_unit,
                'price_per_unit' => $item->price_per_unit,
            ];
        })->toArray();

        $this->treatments = $this->treatmentColony->treatmentColonyTreatmentItems->map(function ($item) {
            return [
                'name' => $item->name,
                'cost' => $item->cost,
            ];
        })->toArray();

        if (empty($this->medicines)) {
            $this->addMedicine();
        }
        
        if (empty($this->treatments)) {
            $this->addAction();
        }
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

    public function save(TreatmentColonyCoreService $coreService)
    {
        $this->validate();

        try {
            $coreService->update($this->farm, $this->treatmentColony->id, [
                'transaction_date' => $this->transaction_date,
                'disease_id' => $this->disease_id,
                'notes' => $this->notes,
                'medicines' => $this->medicines,
                'treatments' => $this->treatments,
            ]);

            session()->flash('success', 'Data treatment koloni berhasil diperbarui.');
            return redirect()->route('admin.care-livestock.treatment-colony.show', [$this->farm->id, $this->treatmentColony->id]);
        } catch (\Throwable $e) {
            Log::error('TreatmentColony Edit Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.treatment-colony.edit-component');
    }
}