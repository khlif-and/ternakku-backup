<?php

namespace App\Livewire\Admin\MutationIndividu;

use Livewire\Component;
use App\Models\Farm;
use App\Models\MutationIndividuD;
use App\Services\Web\Farming\MutationIndividu\MutationIndividuCoreService;
use Illuminate\Support\Facades\Log;

class EditComponent extends Component
{
    public Farm $farm;
    public MutationIndividuD $mutationIndividu;

    public $transaction_date;
    public $pen_destination;
    public $notes;

    public $pens = [];

    protected function rules()
    {
        return [
            'transaction_date' => 'required|date',
            'pen_destination' => 'required|exists:pens,id',
            'notes' => 'nullable|string',
        ];
    }

    protected $messages = [
        'transaction_date.required' => 'Tanggal wajib diisi.',
        'pen_destination.required' => 'Kandang tujuan wajib dipilih.',
    ];

    public function mount(Farm $farm, MutationIndividuD $mutationIndividu)
    {
        $this->farm = $farm;
        $this->mutationIndividu = $mutationIndividu;
        $this->pens = $farm->pens;
        $this->fillFormData();
    }

    public function fillFormData()
    {
        $this->transaction_date = $this->mutationIndividu->mutationH?->transaction_date;
        $this->pen_destination = $this->mutationIndividu->to;
        $this->notes = $this->mutationIndividu->notes;
    }

    public function save(MutationIndividuCoreService $coreService)
    {
        if (!$coreService->checkIsLatest($this->mutationIndividu)) {
            session()->flash('error', 'Perubahan tidak diizinkan karena ini adalah catatan lama.');
            return;
        }

        $this->validate();

        if ($this->pen_destination == $this->mutationIndividu->from) {
            $this->addError('pen_destination', 'Kandang tujuan harus berbeda dengan kandang asal.');
            return;
        }

        try {
            $coreService->update($this->farm, $this->mutationIndividu->id, [
                'transaction_date' => $this->transaction_date,
                'pen_destination' => $this->pen_destination,
                'notes' => $this->notes,
            ]);

            session()->flash('success', 'Data mutasi individu berhasil diperbarui.');
            return redirect()->route('admin.care-livestock.mutation-individu.show', [$this->farm->id, $this->mutationIndividu->id]);
        } catch (\Throwable $e) {
            Log::error('MutationIndividu Edit Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.mutation-individu.edit-component');
    }
}