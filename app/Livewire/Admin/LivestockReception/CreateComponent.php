<?php

namespace App\Livewire\Admin\LivestockReception;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Farm;
use App\Models\Livestock;
use App\Models\LivestockBreed;
use App\Services\Web\Farming\LivestockReception\LivestockReceptionCoreService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CreateComponent extends Component
{
    use WithFileUploads;

    public Farm $farm;

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

    // Phenotype fields
    public $height;
    public $body_length;
    public $hip_height;
    public $hip_width;
    public $chest_width;
    public $head_length;
    public $head_width;
    public $ear_length;
    public $body_weight;

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

    protected $messages = [
        'eartag_number.required' => 'Nomor eartag wajib diisi.',
        'livestock_type_id.required' => 'Jenis ternak wajib dipilih.',
        'livestock_breed_id.required' => 'Ras ternak wajib dipilih.',
        'pen_id.required' => 'Kandang wajib dipilih.',
        'weight.required' => 'Berat wajib diisi.',
        'price_per_kg.required' => 'Harga per kg wajib diisi.',
    ];

    public function mount(Farm $farm)
    {
        $this->farm = $farm;
        $this->transaction_date = now()->format('Y-m-d');
        $this->loadDropdownData();
    }

    public function loadDropdownData()
    {
        $this->livestockTypes = DB::table('livestock_types')->pluck('name', 'id')->toArray();
        $this->sexes = DB::table('livestock_sexes')->pluck('name', 'id')->toArray();
        $this->groups = DB::table('livestock_groups')->pluck('name', 'id')->toArray();
        $this->classifications = DB::table('livestock_classifications')->pluck('name', 'id')->toArray();
    }

    public function updatedLivestockTypeId($value)
    {
        Log::info('updatedLivestockTypeId called with value: ' . $value);
        
        $this->livestock_breed_id = null;
        $this->breeds = [];

        if ($value) {
            $this->breeds = LivestockBreed::where('livestock_type_id', $value)
                ->pluck('name', 'id')
                ->toArray();
            Log::info('Breeds loaded: ' . count($this->breeds));
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
                // Phenotype
                'height' => $this->height,
                'body_length' => $this->body_length,
                'hip_height' => $this->hip_height,
                'hip_width' => $this->hip_width,
                'chest_width' => $this->chest_width,
                'head_length' => $this->head_length,
                'head_width' => $this->head_width,
                'ear_length' => $this->ear_length,
                'body_weight' => $this->body_weight,
            ];

            $coreService->storeReception($this->farm, $data, $photoPath);

            session()->flash('success', 'Registrasi ternak berhasil ditambahkan.');

            return redirect()->route('admin.care-livestock.livestock-reception.index', $this->farm->id);

        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            Log::error('Livestock Reception Create Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.livestock-reception.create-component', [
            'pens' => $this->farm->pens,
        ]);
    }
}
