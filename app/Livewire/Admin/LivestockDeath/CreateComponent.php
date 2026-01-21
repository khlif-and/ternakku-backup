<?php

namespace App\Livewire\Admin\LivestockDeath;

use Livewire\Component;
use App\Models\Farm;
use App\Models\Livestock;
use App\Models\Disease;
use App\Enums\LivestockStatusEnum;
use App\Services\Web\Farming\LivestockDeath\LivestockDeathCoreService;
use Illuminate\Support\Facades\Log;

class CreateComponent extends Component
{
    public Farm $farm;

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

    public function mount(Farm $farm)
    {
        $this->farm = $farm;
        $this->transaction_date = now()->format('Y-m-d');
        $this->loadDropdownData();
    }

    public function loadDropdownData()
    {
        $this->livestocks = Livestock::with(['livestockType', 'livestockBreed'])
            ->where('farm_id', $this->farm->id)
            ->where('livestock_status_id', LivestockStatusEnum::HIDUP->value)
            ->get();

        $this->diseases = Disease::pluck('name', 'id')->toArray();
    }

    public function save(LivestockDeathCoreService $coreService)
    {
        $this->validate();

        try {
            $coreService->storeDeath($this->farm, [
                'transaction_date' => $this->transaction_date,
                'livestock_id' => $this->livestock_id,
                'disease_id' => $this->disease_id,
                'indication' => $this->indication,
                'notes' => $this->notes,
            ]);

            session()->flash('success', 'Data kematian ternak berhasil ditambahkan.');
            return redirect()->route('admin.care-livestock.livestock-death.index', $this->farm->id);
        } catch (\Throwable $e) {
            Log::error('Livestock Death Create Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.livestock-death.create-component');
    }
}
