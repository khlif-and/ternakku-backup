<?php

namespace App\Livewire\Admin\LivestockReception;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Farm;
use App\Helpers\web\LivestockReceptionFormService;
use App\Services\Web\Farming\LivestockReception\LivestockReceptionCoreService;
use Illuminate\Support\Facades\Log;

class CreateComponent extends Component
{
    use WithFileUploads;

    public Farm $farm;

    public $transaction_date;
    public $eartag_number;
    public $rfid_number;
    public $livestock_type_id;
    public $livestock_breed_id;
    public $livestock_sex_id;
    public $livestock_group_id;
    public $livestock_classification_id;
    public $pen_id;
    public $weight;
    public $age;
    public $photo;
    public $notes;

    public $livestockTypes = [];
    public $sexes = [];
    public $groups = [];
    public $classifications = [];
    public $breeds = [];

    protected function rules()
    {
        return [
            'transaction_date' => 'required|date',
            'eartag_number' => 'nullable|string|max:50',
            'rfid_number' => 'nullable|string|max:50',
            'livestock_type_id' => 'required|exists:livestock_types,id',
            'livestock_breed_id' => 'required|exists:livestock_breeds,id',
            'livestock_sex_id' => 'required|exists:livestock_sexes,id',
            'livestock_group_id' => 'nullable|exists:livestock_groups,id',
            'livestock_classification_id' => 'nullable|exists:livestock_classifications,id',
            'pen_id' => 'nullable|exists:pens,id',
            'weight' => 'required|numeric|min:0',
            'age' => 'nullable|integer|min:0',
            'photo' => 'nullable|image|max:5120',
            'notes' => 'nullable|string',
        ];
    }

    protected $messages = [
        'transaction_date.required' => 'Tanggal registrasi wajib diisi.',
        'livestock_type_id.required' => 'Jenis ternak wajib dipilih.',
        'livestock_breed_id.required' => 'Ras ternak wajib dipilih.',
        'livestock_sex_id.required' => 'Jenis kelamin wajib dipilih.',
        'weight.required' => 'Berat wajib diisi.',
    ];

    public function mount(Farm $farm, LivestockReceptionFormService $formService)
    {
        $this->farm = $farm;
        $this->transaction_date = now()->format('Y-m-d');

        $formData = $formService->getDropdownData($farm);
        $this->livestockTypes = $formData['livestockTypes']->toArray();
        $this->sexes = $formData['sexes']->toArray();
        $this->groups = $formData['groups']->toArray();
        $this->classifications = $formData['classifications']->toArray();
    }

    public function updatedLivestockTypeId($value, LivestockReceptionFormService $formService = null)
    {
        $this->livestock_breed_id = null;
        $this->breeds = [];

        if ($value) {
            $formService = $formService ?? app(LivestockReceptionFormService::class);
            $this->breeds = $formService->getBreedsByType((int) $value);
        }
    }

    public function save(LivestockReceptionCoreService $coreService, LivestockReceptionFormService $formService)
    {
        $this->validate();

        try {
            $photoPath = $formService->uploadPhoto($this->photo);

            $data = [
                'transaction_date' => $this->transaction_date,
                'eartag_number' => $this->eartag_number,
                'rfid_number' => $this->rfid_number,
                'livestock_type_id' => $this->livestock_type_id,
                'livestock_breed_id' => $this->livestock_breed_id,
                'livestock_sex_id' => $this->livestock_sex_id,
                'livestock_group_id' => $this->livestock_group_id,
                'livestock_classification_id' => $this->livestock_classification_id,
                'pen_id' => $this->pen_id,
                'weight' => $this->weight,
                'age' => $this->age,
                'notes' => $this->notes,
            ];

            $coreService->storeReception($this->farm, $data, $photoPath);

            session()->flash('success', 'Registrasi ternak berhasil ditambahkan.');

            return redirect()->route('admin.care-livestock.livestock-reception.index', $this->farm->id);

        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            report($e);
            session()->flash('error', 'Terjadi kesalahan pada sistem.');
        }
    }

    public function render()
    {
        return view('livewire.admin.livestock-reception.create-component');
    }
}
