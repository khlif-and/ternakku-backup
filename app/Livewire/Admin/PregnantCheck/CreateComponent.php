<?php

namespace App\Livewire\Admin\PregnantCheck;

use Livewire\Component;
use App\Models\Farm;
use App\Enums\LivestockSexEnum;
use App\Services\Web\Farming\PregnantCheck\PregnantCheckCoreService;
use Illuminate\Support\Facades\Log;

class CreateComponent extends Component
{
    public Farm $farm;

    public $transaction_date;
    public $action_time;
    public $livestock_id;
    public $officer_name;
    public $status;
    public $pregnant_age;
    public $cost = 0;
    public $notes;

    public $livestocks = [];

    // Options for dropdowns
    public $checkStatuses = [
        'PREGNANT' => 'Pregnant (Bunting)',
        'NOT_PREGNANT' => 'Not Pregnant (Tidak Bunting)',
        'INCONCLUSIVE' => 'Inconclusive (Belum Jelas)',
    ];

    protected function rules()
    {
        return [
            'transaction_date' => 'required|date',
            'action_time' => 'required',
            'livestock_id' => 'required|exists:livestocks,id',
            'officer_name' => 'required|string|max:255',
            'status' => 'required|in:PREGNANT,NOT_PREGNANT,INCONCLUSIVE',
            'pregnant_age' => 'required_if:status,PREGNANT|nullable|numeric|min:0',
            'cost' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ];
    }

    protected $messages = [
        'transaction_date.required' => 'Tanggal wajib diisi.',
        'livestock_id.required' => 'Ternak wajib dipilih.',
        'status.required' => 'Status kehamilan wajib dipilih.',
        'pregnant_age.required_if' => 'Usia kehamilan wajib diisi jika status bunting.',
    ];

    public function mount(Farm $farm)
    {
        $this->farm = $farm;
        $this->transaction_date = now()->format('Y-m-d');
        $this->action_time = now()->format('H:i');
        
        // Load only Female livestocks
        $this->livestocks = $farm->livestocks()
            ->where('livestock_sex_id', LivestockSexEnum::BETINA->value)
            ->with(['livestockType', 'livestockBreed', 'pen'])
            ->get();
    }

    public function updatedStatus($value)
    {
        if ($value === 'NOT_PREGNANT') {
            $this->pregnant_age = 0;
        }
    }

    public function save(PregnantCheckCoreService $coreService)
    {
        $this->validate();

        try {
            $coreService->store($this->farm, [
                'transaction_date' => $this->transaction_date,
                'action_time' => $this->action_time,
                'livestock_id' => $this->livestock_id,
                'officer_name' => $this->officer_name,
                'status' => $this->status,
                'pregnant_age' => $this->pregnant_age,
                'cost' => $this->cost,
                'notes' => $this->notes,
            ]);

            session()->flash('success', 'Data pemeriksaan kehamilan berhasil ditambahkan.');
            
            // Redirect ke index sesuai flow Controller sebelumnya
            return redirect()->route('admin.care_livestock.pregnant_check.index', ['farm_id' => $this->farm->id]);

        } catch (\Throwable $e) {
            Log::error('PregnantCheck Create Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.pregnant-check.create-component');
    }
}