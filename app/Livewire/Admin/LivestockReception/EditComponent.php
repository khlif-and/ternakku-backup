<?php

namespace App\Livewire\Admin\LivestockReception;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Farm;
use App\Models\LivestockBreed;
use App\Models\LivestockReceptionD;
use App\Services\Web\Farming\LivestockReception\LivestockReceptionCoreService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EditComponent extends Component
{
    use WithFileUploads;

    public Farm $farm;
    public LivestockReceptionD $reception;

    // Form fields
    public $transaction_date;
    public $supplier;
    public $eartag_number;
    public $rfid_number;
    public $livestock_type_id;
    public $livestock_sex_id;
    public $livestock_group_id;
    public $livestock_breed_id;
    public $livestock_classification_id;
    public $pen_id;
    public $age_years = 0;
    public $age_months = 0;
    public $weight;
    public $price_per_kg;
    public $price_per_head;
    public $notes;
    public $characteristics;
    public $photo;
    public $existing_photo;

    // Dropdown data
    public $livestockTypes = [];
    public $sexes = [];
    public $groups = [];
    public $classifications = [];
    public $breeds = [];

    protected function rules()
    {
        return [
            'transaction_date' => 'required|date',
            'supplier' => 'nullable|string|max:255',
            'eartag_number' => 'required|string|max:255',
            'rfid_number' => 'nullable|string|max:255',
            'livestock_type_id' => 'required|exists:livestock_types,id',
            'livestock_sex_id' => 'required|exists:livestock_sexes,id',
            'livestock_group_id' => 'required|exists:livestock_groups,id',
            'livestock_breed_id' => 'required|exists:livestock_breeds,id',
            'livestock_classification_id' => 'required|exists:livestock_classifications,id',
            'pen_id' => 'required|exists:pens,id',
            'age_years' => 'required|integer|min:0',
            'age_months' => 'required|integer|min:0|max:11',
            'weight' => 'required|numeric|min:0',
            'price_per_kg' => 'required|numeric|min:0',
            'price_per_head' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'characteristics' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
        ];
    }

    public function mount(Farm $farm, LivestockReceptionD $reception)
    {
        $this->farm = $farm;
        $this->reception = $reception;
        $this->loadDropdownData();
        $this->fillFormData();
    }

    public function loadDropdownData()
    {
        $this->livestockTypes = DB::table('livestock_types')->pluck('name', 'id')->toArray();
        $this->sexes = DB::table('livestock_sexes')->pluck('name', 'id')->toArray();
        $this->groups = DB::table('livestock_groups')->pluck('name', 'id')->toArray();
        $this->classifications = DB::table('livestock_classifications')->pluck('name', 'id')->toArray();
    }

    public function fillFormData()
    {
        $this->transaction_date = $this->reception->livestockReceptionH->transaction_date;
        $this->supplier = $this->reception->livestockReceptionH->supplier ?? '';
        $this->eartag_number = $this->reception->eartag_number;
        $this->rfid_number = $this->reception->rfid_number;
        $this->livestock_type_id = $this->reception->livestock_type_id;
        $this->livestock_sex_id = $this->reception->livestock_sex_id;
        $this->livestock_group_id = $this->reception->livestock_group_id;
        $this->livestock_breed_id = $this->reception->livestock_breed_id;
        $this->livestock_classification_id = $this->reception->livestock_classification_id;
        $this->pen_id = $this->reception->pen_id;
        $this->age_years = $this->reception->age_years;
        $this->age_months = $this->reception->age_months;
        $this->weight = $this->reception->weight;
        $this->price_per_kg = $this->reception->price_per_kg;
        $this->price_per_head = $this->reception->price_per_head;
        $this->notes = $this->reception->notes;
        $this->characteristics = $this->reception->characteristics;
        $this->existing_photo = $this->reception->photo;

        // Load breeds for the selected type
        $this->breeds = LivestockBreed::where('livestock_type_id', $this->livestock_type_id)
            ->pluck('name', 'id')
            ->toArray();
    }

    public function updatedLivestockTypeId($value)
    {
        $this->livestock_breed_id = null;
        $this->breeds = [];

        if ($value) {
            $this->breeds = LivestockBreed::where('livestock_type_id', $value)
                ->pluck('name', 'id')
                ->toArray();
        }
    }

    public function updatedWeight($value)
    {
        $this->calculatePricePerHead();
    }

    public function updatedPricePerKg($value)
    {
        $this->calculatePricePerHead();
    }

    private function calculatePricePerHead()
    {
        if ($this->weight && $this->price_per_kg) {
            $this->price_per_head = (float) $this->weight * (float) $this->price_per_kg;
        }
    }

    public function save(LivestockReceptionCoreService $coreService)
    {
        $this->validate();

        try {
            $photoPath = null;

            if ($this->photo) {
                // Delete old photo
                if ($this->existing_photo && file_exists(public_path($this->existing_photo))) {
                    unlink(public_path($this->existing_photo));
                }
                $fileName = time() . '-' . $this->photo->getClientOriginalName();
                $this->photo->storeAs('receptions', $fileName, 'public');
                $photoPath = 'storage/receptions/' . $fileName;
            }

            $data = [
                'transaction_date' => $this->transaction_date,
                'supplier' => $this->supplier,
                'eartag_number' => $this->eartag_number,
                'rfid_number' => $this->rfid_number,
                'livestock_type_id' => $this->livestock_type_id,
                'livestock_sex_id' => $this->livestock_sex_id,
                'livestock_group_id' => $this->livestock_group_id,
                'livestock_breed_id' => $this->livestock_breed_id,
                'livestock_classification_id' => $this->livestock_classification_id,
                'pen_id' => $this->pen_id,
                'age_years' => $this->age_years,
                'age_months' => $this->age_months,
                'weight' => $this->weight,
                'price_per_kg' => $this->price_per_kg,
                'price_per_head' => $this->price_per_head,
                'notes' => $this->notes,
                'characteristics' => $this->characteristics,
            ];

            $coreService->updateReception($this->farm, $this->reception->id, $data, $photoPath);

            session()->flash('success', 'Data registrasi ternak berhasil diperbarui.');

            return redirect()->route('admin.care-livestock.livestock-reception.index', $this->farm->id);

        } catch (\Throwable $e) {
            Log::error('Livestock Reception Update Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.livestock-reception.edit-component', [
            'pens' => $this->farm->pens,
        ]);
    }
}
