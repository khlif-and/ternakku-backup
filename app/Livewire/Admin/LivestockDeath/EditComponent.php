<?php

namespace App\Livewire\Admin\LivestockDeath;

use Livewire\Component;
use App\Models\Farm;
use App\Models\Livestock;
use App\Models\LivestockDeath;
use App\Models\Disease;
use App\Enums\LivestockStatusEnum;
use App\Services\Web\Farming\LivestockDeath\LivestockDeathCoreService;
use Illuminate\Support\Facades\Log;

class EditComponent extends Component
{
    public Farm $farm;
    public LivestockDeath $death;

    public $transaction_date;
    public $livestock_id;
    public $disease_id;
    public $indication;
    public $notes;

    public $livestocks = [];
    public $diseases = [];

    protected function rules()
    {
        return [
            'transaction_date' => 'required|date',
            'livestock_id' => 'required|exists:livestocks,id',
            'disease_id' => 'nullable|exists:diseases,id',
            'indication' => 'nullable|string|max:500',
            'notes' => 'nullable|string',
        ];
    }

    protected $messages = [
        'transaction_date.required' => 'Tanggal wajib diisi.',
        'livestock_id.required' => 'Ternak wajib dipilih.',
    ];

    public function mount(Farm $farm, LivestockDeath $death)
    {
        $this->farm = $farm;
        $this->death = $death;
        $this->fillFormData();
        $this->loadDropdownData();
    }

    public function fillFormData()
    {
        $this->transaction_date = $this->death->transaction_date?->format('Y-m-d');
        $this->livestock_id = $this->death->livestock_id;
        $this->disease_id = $this->death->disease_id;
        $this->indication = $this->death->indication;
        $this->notes = $this->death->notes;
    }

    public function loadDropdownData()
    {
        $this->livestocks = Livestock::with(['livestockType', 'livestockBreed'])
            ->where('farm_id', $this->farm->id)
            ->where(function ($q) {
                $q->where('livestock_status_id', LivestockStatusEnum::HIDUP->value)
                  ->orWhere('id', $this->death->livestock_id);
            })
            ->get();

        $this->diseases = Disease::pluck('name', 'id')->toArray();
    }

    public function save(LivestockDeathCoreService $coreService)
    {
        $this->validate();

        try {
            $coreService->updateDeath($this->farm, $this->death->id, [
                'transaction_date' => $this->transaction_date,
                'livestock_id' => $this->livestock_id,
                'disease_id' => $this->disease_id,
                'indication' => $this->indication,
                'notes' => $this->notes,
            ]);

            session()->flash('success', 'Data kematian ternak berhasil diperbarui.');
            return redirect()->route('admin.care-livestock.livestock-death.index', $this->farm->id);
        } catch (\Throwable $e) {
            Log::error('Livestock Death Edit Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.livestock-death.edit-component');
    }
}
