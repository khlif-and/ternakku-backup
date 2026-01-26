<?php

namespace App\Livewire\Admin\LivestockBirth;

use Livewire\Component;
use App\Models\Farm;
use App\Models\LivestockBreed;
use App\Models\Disease;
use App\Services\Web\Farming\LivestockBirth\LivestockBirthCoreService;
use App\Enums\LivestockSexEnum;
use Illuminate\Support\Facades\Log;

class CreateComponent extends Component
{
    public Farm $farm;

    // Form Fields
    public $transaction_date;
    public $livestock_id; // Indukan (Dam)
    public $officer_name;
    public $cost = 0;
    public $status = 'NORMAL'; // NORMAL, ABNORMAL, ABORTUS
    public $estimated_weaning;
    public $notes;

    // Dynamic Details (Anak Ternak)
    public $details = [];

    // Lists for Select Options
    public $femaleLivestocks = [];
    public $breeds = [];
    public $diseases = [];
    public $sexes = []; // Opsional, bisa pakai Enum di view atau array statis

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
            'livestock_id' => 'required|exists:livestocks,id',
            'officer_name' => 'nullable|string|max:255',
            'cost' => 'required|numeric|min:0',
            'status' => 'required|in:NORMAL,PREMATURE,ABORTUS',
            'estimated_weaning' => 'nullable|date|after_or_equal:transaction_date',
            'notes' => 'nullable|string',
        ];

        // Validasi details hanya jika status bukan ABORTUS
        if ($this->status !== 'ABORTUS') {
            $rules = array_merge($rules, [
                'details' => 'required|array|min:1',
                'details.*.livestock_sex_id' => 'required', // Sesuaikan dengan tipe data ID sex
                'details.*.livestock_breed_id' => 'required|exists:livestock_breeds,id',
                'details.*.weight' => 'required|numeric|min:0',
                'details.*.birth_order' => 'required|integer|min:1',
                'details.*.status' => 'required|in:alive,dead',
                // Conditional validation based on offspring status
                'details.*.offspring_value' => 'required_if:details.*.status,alive|nullable|numeric|min:0',
                'details.*.disease_id' => 'required_if:details.*.status,dead|nullable|exists:diseases,id',
                'details.*.indication' => 'required_if:details.*.status,dead|nullable|string',
            ]);
        }

        return $rules;
    }

    protected $messages = [
        'livestock_id.required' => 'Indukan wajib dipilih.',
        'details.required' => 'Detail anak ternak wajib diisi (kecuali Abortus).',
        'details.min' => 'Minimal satu data anak ternak.',
        'details.*.offspring_value.required_if' => 'Nilai anak wajib diisi jika hidup.',
        'details.*.disease_id.required_if' => 'Penyakit wajib dipilih jika mati.',
    ];

    public function mount(Farm $farm)
    {
        $this->farm = $farm;
        $this->transaction_date = now()->format('Y-m-d');
        
        // Load Data
        $this->femaleLivestocks = $farm->livestocks()
            ->where('livestock_sex_id', LivestockSexEnum::BETINA->value)
            ->get();
            
        $this->breeds = LivestockBreed::all();
        $this->diseases = Disease::all();
        
        // Default satu baris detail
        $this->addDetail();
    }

    // Handle Dynamic Details
    public function addDetail()
    {
        $this->details[] = [
            'livestock_sex_id' => '',
            'livestock_breed_id' => '',
            'weight' => 0,
            'birth_order' => count($this->details) + 1,
            'status' => 'alive', // default alive
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
            
            // Re-index birth order (opsional, tergantung kebutuhan bisnis)
            foreach ($this->details as $k => $v) {
                $this->details[$k]['birth_order'] = $k + 1;
            }
        }
    }

    // Reset details jika status berubah jadi ABORTUS
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
            // Persiapkan data
            $data = [
                'transaction_date' => $this->transaction_date,
                'livestock_id' => $this->livestock_id,
                'officer_name' => $this->officer_name,
                'cost' => $this->cost,
                'status' => $this->status,
                'estimated_weaning' => $this->estimated_weaning,
                'notes' => $this->notes,
                'details' => $this->status !== 'ABORTUS' ? $this->details : null,
            ];

            $birth = $coreService->storeBirth($this->farm, $data);

            session()->flash('success', 'Data kelahiran berhasil ditambahkan.');
            return redirect()->route('admin.care_livestock.livestock_birth.show', [$this->farm->id, $birth->id]);

        } catch (\Throwable $e) {
            Log::error('LivestockBirth Create Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.livestock-birth.create-component');
    }
}