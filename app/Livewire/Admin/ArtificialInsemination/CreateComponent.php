<?php

namespace App\Livewire\Admin\ArtificialInsemination;

use Livewire\Component;
use App\Models\Farm;
use App\Models\Livestock;
use App\Models\LivestockBreed;
use App\Services\Web\Farming\ArtificialInsemination\ArtificialInseminationCoreService;
use Illuminate\Support\Facades\Log;

class CreateComponent extends Component
{
    public Farm $farm;

    public $transaction_date;
    public $action_time;
    public $livestock_id;
    public $officer_name;
    public $semen_breed_id;
    public $sire_name;
    public $semen_producer;
    public $semen_batch;
    public $cost = 0;
    public $notes;

    public $livestocks = [];
    public $breeds = [];

    protected function rules()
    {
        return [
            'transaction_date' => 'required|date',
            'action_time' => 'required',
            'livestock_id' => 'required|exists:livestocks,id',
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
        'livestock_id.required' => 'Betina induk wajib dipilih.',
        'semen_breed_id.required' => 'Ras semen wajib dipilih.',
        'officer_name.required' => 'Nama petugas/inseminator wajib diisi.',
    ];

    public function mount(Farm $farm)
    {
        $this->farm = $farm;
        $this->transaction_date = now()->format('Y-m-d');
        $this->action_time = now()->format('H:i');
        
        $this->livestocks = Livestock::where('farm_id', $this->farm->id)
            ->whereHas('livestockSex', function($q) {
                $q->where('name', 'Female')->orWhere('name', 'Betina');
            })->get();

        $this->breeds = LivestockBreed::all();
    }

    public function save(ArtificialInseminationCoreService $coreService)
    {
        $this->validate();

        try {
            $aiRecord = $coreService->store($this->farm, [
                'transaction_date' => $this->transaction_date,
                'action_time' => $this->action_time,
                'livestock_id' => $this->livestock_id,
                'officer_name' => $this->officer_name,
                'semen_breed_id' => $this->semen_breed_id,
                'sire_name' => $this->sire_name,
                'semen_producer' => $this->semen_producer,
                'semen_batch' => $this->semen_batch,
                'cost' => $this->cost,
                'notes' => $this->notes,
            ]);

            session()->flash('success', 'Data Inseminasi Buatan berhasil disimpan.');
            return redirect()->route('admin.care-livestock.artificial-inseminasi.show', [$this->farm->id, $aiRecord->id]);
            
        } catch (\Throwable $e) {
            Log::error('AI Create Component Error: ' . $e->getMessage());
            session()->flash('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.artificial-insemination.create-component');
    }
}