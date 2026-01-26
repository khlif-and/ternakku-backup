<?php

namespace App\Livewire\Admin\LivestockBirth;

use Livewire\Component;
use App\Models\Farm;
use App\Models\LivestockBirth;
use App\Models\LivestockBreed;
use App\Models\Disease;
use App\Services\Web\Farming\LivestockBirth\LivestockBirthCoreService;
use App\Enums\LivestockSexEnum;
use Illuminate\Support\Facades\Log;

class EditComponent extends Component
{
    public Farm $farm;
    public LivestockBirth $birth;

    // Form Fields
    public $transaction_date;
    public $livestock_id; // Readonly (biasanya indukan tidak diubah saat edit)
    public $officer_name;
    public $cost = 0;
    public $status;
    public $estimated_weaning;
    public $notes;

    // Dynamic Details
    public $details = [];

    // Lists
    public $femaleLivestocks = [];
    public $breeds = [];
    public $diseases = [];

    // Options for dropdowns
    public $birthStatuses = [
        'NORMAL' => 'Normal',
        'PREMATURE' => 'Premature',
        'ABORTUS' => 'Abortus (Keguguran)',
    ];

    public $offspringStatuses = [
        'alive' => 'Hidup (Alive)',
        'dead' => 'Mati (Dead)',
    ];

    protected function rules()
    {
        $rules = [
            'transaction_date' => 'required|date',
            'officer_name' => 'nullable|string|max:255',
            'cost' => 'required|numeric|min:0',
            'status' => 'required|in:NORMAL,PREMATURE,ABORTUS',
            'estimated_weaning' => 'nullable|date|after_or_equal:transaction_date',
            'notes' => 'nullable|string',
        ];

        if ($this->status !== 'ABORTUS') {
            $rules = array_merge($rules, [
                'details' => 'required|array|min:1',
                'details.*.livestock_sex_id' => 'required',
                'details.*.livestock_breed_id' => 'required|exists:livestock_breeds,id',
                'details.*.weight' => 'required|numeric|min:0',
                'details.*.birth_order' => 'required|integer|min:1',
                'details.*.status' => 'required|in:alive,dead',
                'details.*.offspring_value' => 'required_if:details.*.status,alive|nullable|numeric|min:0',
                'details.*.disease_id' => 'required_if:details.*.status,dead|nullable|exists:diseases,id',
                'details.*.indication' => 'required_if:details.*.status,dead|nullable|string',
            ]);
        }

        return $rules;
    }

    protected $messages = [
        'transaction_date.required' => 'Tanggal wajib diisi.',
        'details.required' => 'Detail anak ternak wajib diisi.',
        'details.*.offspring_value.required_if' => 'Nilai anak wajib diisi jika hidup.',
        'details.*.disease_id.required_if' => 'Penyakit wajib dipilih jika mati.',
    ];

    public function mount(Farm $farm, LivestockBirth $birth)
    {
        $this->farm = $farm;
        $this->birth = $birth;
        
        // Load Lists
        $this->femaleLivestocks = $farm->livestocks()
            ->where('livestock_sex_id', LivestockSexEnum::BETINA->value)
            ->get();
        $this->breeds = LivestockBreed::all();
        $this->diseases = Disease::all();

        $this->fillFormData();
    }

    public function fillFormData()
    {
        $this->transaction_date = $this->birth->transaction_date;
        $this->livestock_id = $this->birth->reproductionCycle->livestock_id; // Ambil dari relasi
        $this->officer_name = $this->birth->officer_name;
        $this->cost = $this->birth->cost;
        $this->status = $this->birth->status;
        $this->estimated_weaning = $this->birth->estimated_weaning;
        $this->notes = $this->birth->notes;

        $this->details = $this->birth->livestockBirthD->map(function ($item) {
            return [
                'livestock_sex_id' => $item->livestock_sex_id,
                'livestock_breed_id' => $item->livestock_breed_id,
                'weight' => $item->weight,
                'birth_order' => $item->birth_order,
                'status' => $item->status,
                'offspring_value' => $item->offspring_value,
                'disease_id' => $item->disease_id,
                'indication' => $item->indication,
            ];
        })->toArray();

        // Jika status bukan abortus tapi details kosong (misal data lama error), tambahkan row default
        if ($this->status !== 'ABORTUS' && empty($this->details)) {
            $this->addDetail();
        }
    }

    public function addDetail()
    {
        $this->details[] = [
            'livestock_sex_id' => '',
            'livestock_breed_id' => '',
            'weight' => 0,
            'birth_order' => count($this->details) + 1,
            'status' => 'alive',
            'offspring_value' => 0,
            'disease_id' => null,
            'indication' => null,
        ];
    }

    public function removeDetail($index)
    {
        if (count($this->details) > 1) {
            unset($this->details[$index]);
            $this->details = array_values($this->details);
            
            // Re-index birth order
            foreach ($this->details as $k => $v) {
                $this->details[$k]['birth_order'] = $k + 1;
            }
        }
    }

    public function updatedStatus($value)
    {
        if ($value === 'ABORTUS') {
            $this->details = [];
        } elseif (empty($this->details)) {
            $this->addDetail();
        }
    }

    public function save(LivestockBirthCoreService $coreService)
    {
        $this->validate();

        try {
            $coreService->updateBirth($this->farm, $this->birth->id, [
                'transaction_date' => $this->transaction_date,
                // livestock_id biasanya tidak diupdate di core service karena terikat cycle
                'officer_name' => $this->officer_name,
                'cost' => $this->cost,
                'status' => $this->status,
                'estimated_weaning' => $this->estimated_weaning,
                'notes' => $this->notes,
                'details' => $this->status !== 'ABORTUS' ? $this->details : null,
            ]);

            session()->flash('success', 'Data kelahiran berhasil diperbarui.');
            return redirect()->route('admin.care_livestock.livestock_birth.show', [$this->farm->id, $this->birth->id]);
        } catch (\Throwable $e) {
            Log::error('LivestockBirth Edit Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.livestock-birth.edit-component');
    }
}