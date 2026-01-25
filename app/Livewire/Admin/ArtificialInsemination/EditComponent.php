<?php

namespace App\Livewire\Admin\ArtificialInsemination;

use Livewire\Component;
use App\Models\Farm;
use App\Models\InseminationArtificial;
use App\Models\LivestockBreed;
use App\Services\Web\Farming\ArtificialInsemination\ArtificialInseminationCoreService;
use Illuminate\Support\Facades\Log;

class EditComponent extends Component
{
    public Farm $farm;
    public InseminationArtificial $aiRecord;

    public $transaction_date;
    public $action_time;
    public $officer_name;
    public $semen_breed_id;
    public $sire_name;
    public $semen_producer;
    public $semen_batch;
    public $cost;
    public $notes;

    public $breeds = [];

    protected function rules()
    {
        return [
            'transaction_date' => 'required|date',
            'action_time' => 'required',
            'officer_name' => 'required|string|max:255',
            'semen_breed_id' => 'required|exists:livestock_breeds,id',
            'sire_name' => 'nullable|string|max:255',
            'semen_producer' => 'nullable|string|max:255',
            'semen_batch' => 'nullable|string|max:255',
            'cost' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ];
    }

    protected $messages = [
        'transaction_date.required' => 'Tanggal IB wajib diisi.',
        'action_time.required' => 'Waktu tindakan wajib diisi.',
        'officer_name.required' => 'Nama petugas wajib diisi.',
        'semen_breed_id.required' => 'Ras semen wajib dipilih.',
    ];

    public function mount(Farm $farm, InseminationArtificial $item)
    {
        $this->farm = $farm;
        $this->aiRecord = $item;
        $this->breeds = LivestockBreed::all();
        $this->fillFormData();
    }

    public function fillFormData()
    {
        $this->transaction_date = $this->aiRecord->insemination?->transaction_date;
        $this->action_time = $this->aiRecord->action_time;
        $this->officer_name = $this->aiRecord->officer_name;
        $this->semen_breed_id = $this->aiRecord->semen_breed_id;
        $this->sire_name = $this->aiRecord->sire_name;
        $this->semen_producer = $this->aiRecord->semen_producer;
        $this->semen_batch = $this->aiRecord->semen_batch;
        $this->cost = $this->aiRecord->cost;
        $this->notes = $this->aiRecord->insemination?->notes;
    }

    public function save(ArtificialInseminationCoreService $coreService)
    {
        $this->validate();

        try {
            $coreService->update($this->farm, $this->aiRecord->id, [
                'transaction_date' => $this->transaction_date,
                'action_time' => $this->action_time,
                'officer_name' => $this->officer_name,
                'semen_breed_id' => $this->semen_breed_id,
                'sire_name' => $this->sire_name,
                'semen_producer' => $this->semen_producer,
                'semen_batch' => $this->semen_batch,
                'cost' => $this->cost,
                'notes' => $this->notes,
            ]);

            session()->flash('success', 'Data Inseminasi Buatan berhasil diperbarui.');
            return redirect()->route('admin.care-livestock.artificial-inseminasi.show', [$this->farm->id, $this->aiRecord->id]);
        } catch (\Throwable $e) {
            Log::error('ArtificialInsemination Edit Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.artificial-insemination.edit-component');
    }
}