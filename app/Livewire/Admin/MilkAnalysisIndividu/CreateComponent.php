<?php

namespace App\Livewire\Admin\MilkAnalysisIndividu;

use Livewire\Component;
use App\Models\Farm;
use App\Services\Web\Farming\MilkAnalysisIndividu\MilkAnalysisIndividuCoreService;
use App\Enums\LivestockSexEnum;
use Illuminate\Support\Facades\Log;

class CreateComponent extends Component
{
    public Farm $farm;

    public $transaction_date;
    public $livestock_id;
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

    public $livestocks = [];

    protected function rules()
    {
        return [
            'transaction_date' => 'required|date',
            'livestock_id' => 'required|exists:livestocks,id',
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
        'livestock_id.required' => 'Ternak wajib dipilih.',
    ];

    public function mount(Farm $farm)
    {
        $this->farm = $farm;
        $this->transaction_date = now()->format('Y-m-d');
        
        $this->livestocks = $this->farm->livestocks()
            ->where('livestock_sex_id', LivestockSexEnum::BETINA->value)
            ->get();
    }

    public function save(MilkAnalysisIndividuCoreService $coreService)
    {
        $this->validate();

        try {
            $data = [
                'transaction_date' => $this->transaction_date,
                'livestock_id' => $this->livestock_id,
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

            session()->flash('success', 'Data analisis susu individu berhasil disimpan.');
            
            return redirect()->route('admin.care-livestock.milk-analysis-individu.show', [
                $this->farm->id,
                $record->id
            ]);
        } catch (\Throwable $e) {
            Log::error('MilkAnalysisIndividu Save Error', [
                'message' => $e->getMessage(),
            ]);
            session()->flash('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.milk-analysis-individu.create-component');
    }
}