<?php

namespace App\Livewire\Admin\MutationIndividu;

use Livewire\Component;
use App\Models\Farm;
use App\Services\Web\Farming\MutationIndividu\MutationIndividuCoreService;
use Illuminate\Support\Facades\Log;

class CreateComponent extends Component
{
    public Farm $farm;

    public $transaction_date;
    public $livestock_id;
    public $pen_destination;
    public $notes;

    public $livestocks = [];
    public $pens = [];

    protected function rules()
    {
        return [
            'transaction_date' => 'required|date',
            'livestock_id' => 'required|exists:livestocks,id',
            'pen_destination' => 'required|exists:pens,id',
            'notes' => 'nullable|string',
        ];
    }

    protected $messages = [
        'transaction_date.required' => 'Tanggal mutasi wajib diisi.',
        'livestock_id.required' => 'Hewan ternak wajib dipilih.',
        'pen_destination.required' => 'Kandang tujuan wajib dipilih.',
        'pen_destination.exists' => 'Kandang tujuan tidak valid.',
    ];

    public function mount(Farm $farm)
    {
        $this->farm = $farm;
        $this->transaction_date = now()->format('Y-m-d');
        $this->livestocks = $farm->livestocks()->get();
        $this->pens = $farm->pens()->get();
    }

    public function save(MutationIndividuCoreService $coreService)
    {
        $this->validate();

        $livestock = $this->farm->livestocks()->find($this->livestock_id);

        if ($livestock && $this->pen_destination == $livestock->pen_id) {
            $this->addError('pen_destination', 'Kandang tujuan harus berbeda dengan kandang saat ini.');
            return;
        }

        try {
            $mutationIndividuD = $coreService->store($this->farm, [
                'transaction_date' => $this->transaction_date,
                'livestock_id' => $this->livestock_id,
                'pen_destination' => $this->pen_destination,
                'notes' => $this->notes,
            ]);

            session()->flash('success', 'Mutasi individu berhasil dicatat.');
            return redirect()->route('admin.care-livestock.mutation-individu.show', [$this->farm->id, $mutationIndividuD->id]);
        } catch (\Throwable $e) {
            Log::error('MutationIndividu Create Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.mutation-individu.create-component');
    }
}