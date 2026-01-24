<?php

namespace App\Livewire\Admin\MilkAnalysisIndividu;

use Livewire\Component;
use App\Models\Farm;
use App\Models\MilkAnalysisIndividuD;
use App\Services\Web\Farming\MilkAnalysisIndividu\MilkAnalysisIndividuCoreService;
use App\Enums\LivestockSexEnum;
use Illuminate\Support\Facades\Log;

class EditComponent extends Component
{
    public Farm $farm;
    public MilkAnalysisIndividuD $milkAnalysisIndividu;

    public $transaction_date;
    public $livestock_id;
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

    public function mount(Farm $farm, MilkAnalysisIndividuD $milkAnalysisIndividu)
    {
        $this->farm = $farm;
        $this->milkAnalysisIndividu = $milkAnalysisIndividu;
        
        $this->livestocks = $this->farm->livestocks()
            ->where('livestock_sex_id', LivestockSexEnum::BETINA->value)
            ->get();

        $this->fillFormData();
    }

    public function fillFormData()
    {
        $this->transaction_date = $this->milkAnalysisIndividu->milkAnalysisH->transaction_date;
        $this->livestock_id = $this->milkAnalysisIndividu->livestock_id;
        $this->bj = $this->milkAnalysisIndividu->bj;
        $this->at = (bool) $this->milkAnalysisIndividu->at;
        $this->ab = (bool) $this->milkAnalysisIndividu->ab;
        $this->mbrt = $this->milkAnalysisIndividu->mbrt;
        $this->a_water = $this->milkAnalysisIndividu->a_water;
        $this->protein = $this->milkAnalysisIndividu->protein;
        $this->fat = $this->milkAnalysisIndividu->fat;
        $this->snf = $this->milkAnalysisIndividu->snf;
        $this->ts = $this->milkAnalysisIndividu->ts;
        $this->notes = $this->milkAnalysisIndividu->notes;
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

            $coreService->updateAnalysis($this->farm, $this->milkAnalysisIndividu->id, $data);

            session()->flash('success', 'Data analisis susu individu berhasil diperbarui.');
            
            return redirect()->route('admin.care-livestock.milk-analysis-individu.show', [
                $this->farm->id,
                $this->milkAnalysisIndividu->id
            ]);
        } catch (\Throwable $e) {
            Log::error('MilkAnalysisIndividu Edit Error', [
                'message' => $e->getMessage(),
            ]);
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.milk-analysis-individu.edit-component');
    }
}