<?php

namespace App\Livewire\Admin\MilkAnalysisGlobal;

use Livewire\Component;
use App\Models\Farm;
use App\Services\Web\Farming\MilkAnalysisGlobal\MilkAnalysisGlobalCoreService;
use Illuminate\Support\Facades\Log;

class CreateComponent extends Component
{
    public Farm $farm;

    public $transaction_date;
    public $bj;
    public $at = false;
    public $ab = false;
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

    public function mount(Farm $farm)
    {
        $this->farm = $farm;
        $this->transaction_date = now()->format('Y-m-d');
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

            $record = $coreService->storeAnalysis($this->farm, $data);

            session()->flash('success', 'Data analisis susu global berhasil disimpan.');
            
            return redirect()->route('admin.care-livestock.milk-analysis-global.show', [
                $this->farm->id,
                $record->id
            ]);
        } catch (\Throwable $e) {
            Log::error('MilkAnalysisGlobal Save Error', [
                'message' => $e->getMessage(),
            ]);
            session()->flash('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.milk-analysis-global.create-component');
    }
}