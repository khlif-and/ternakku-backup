<?php

namespace App\Livewire\Admin\MilkAnalysisGlobal;

use Livewire\Component;
use App\Models\Farm;
use App\Models\MilkAnalysisGlobal;
use App\Services\Web\Farming\MilkAnalysisGlobal\MilkAnalysisGlobalCoreService;
use Illuminate\Support\Facades\Log;

class EditComponent extends Component
{
    public Farm $farm;
    public MilkAnalysisGlobal $milkAnalysisGlobal;

    public $transaction_date;
    public $bj;
    public $at;
    public $ab;
    public $mbrt;
    public $a_water;
    public $protein;
    public $fat;
    public $snf;
    public $ts;
    public $notes;

    protected function rules()
    {
        return [
            'transaction_date' => 'required|date',
            'bj' => 'nullable|numeric',
            'at' => 'nullable|boolean',
            'ab' => 'nullable|boolean',
            'mbrt' => 'nullable|numeric',
            'a_water' => 'nullable|numeric',
            'protein' => 'nullable|numeric',
            'fat' => 'nullable|numeric',
            'snf' => 'nullable|numeric',
            'ts' => 'nullable|numeric',
            'notes' => 'nullable|string',
        ];
    }

    protected $messages = [
        'transaction_date.required' => 'Tanggal wajib diisi.',
    ];

    public function mount(Farm $farm, MilkAnalysisGlobal $milkAnalysisGlobal)
    {
        $this->farm = $farm;
        $this->milkAnalysisGlobal = $milkAnalysisGlobal;
        $this->fillFormData();
    }

    public function fillFormData()
    {
        $this->transaction_date = $this->milkAnalysisGlobal->transaction_date;
        $this->bj = $this->milkAnalysisGlobal->bj;
        $this->at = (bool) $this->milkAnalysisGlobal->at;
        $this->ab = (bool) $this->milkAnalysisGlobal->ab;
        $this->mbrt = $this->milkAnalysisGlobal->mbrt;
        $this->a_water = $this->milkAnalysisGlobal->a_water;
        $this->protein = $this->milkAnalysisGlobal->protein;
        $this->fat = $this->milkAnalysisGlobal->fat;
        $this->snf = $this->milkAnalysisGlobal->snf;
        $this->ts = $this->milkAnalysisGlobal->ts;
        $this->notes = $this->milkAnalysisGlobal->notes;
    }

    public function save(MilkAnalysisGlobalCoreService $coreService)
    {
        $this->validate();

        try {
            $data = [
                'transaction_date' => $this->transaction_date,
                'bj' => $this->bj,
                'at' => $this->at ? 1 : 0,
                'ab' => $this->ab ? 1 : 0,
                'mbrt' => $this->mbrt,
                'a_water' => $this->a_water,
                'protein' => $this->protein,
                'fat' => $this->fat,
                'snf' => $this->snf,
                'ts' => $this->ts,
                'notes' => $this->notes,
            ];

            $coreService->updateAnalysis($this->farm, $this->milkAnalysisGlobal->id, $data);

            session()->flash('success', 'Data analisis susu global berhasil diperbarui.');
            
            return redirect()->route('admin.care-livestock.milk-analysis-global.show', [
                $this->farm->id,
                $this->milkAnalysisGlobal->id
            ]);
        } catch (\Throwable $e) {
            Log::error('MilkAnalysisGlobal Edit Error', [
                'message' => $e->getMessage(),
            ]);
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.milk-analysis-global.edit-component');
    }
}