<?php

namespace App\Livewire\Reports\CareLivestock\TreatmentColonyReport;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Farm;
use App\Models\TreatmentColonyD;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    use WithPagination;

    public Farm $farm;
    public $start_date;
    public $end_date;
    public $pen_id = '';
    public $showReport = false;

    public $statistics = [];

    protected $queryString = ['start_date', 'end_date', 'pen_id'];

    public function mount(Farm $farm)
    {
        $this->farm = $farm;
        $this->start_date = request('start_date', now()->format('Y-m-d'));
        $this->end_date = request('end_date', now()->format('Y-m-d'));

        if (request('start_date')) {
            $this->generateReport();
        }
    }

    public function generateReport()
    {
        $this->showReport = true;
        $this->resetPage();
    }

    public function getTreatmentDataProperty()
    {
        if (!$this->showReport) {
            return new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
        }

        return TreatmentColonyD::select('treatment_colony_d.*')
            ->join('treatment_h', 'treatment_colony_d.treatment_h_id', '=', 'treatment_h.id')
            ->where('treatment_h.farm_id', $this->farm->id)
            ->where('treatment_h.type', 'colony')
            ->whereBetween('treatment_h.transaction_date', [$this->start_date, $this->end_date])
            ->when($this->pen_id, function ($q) {
                $q->where('treatment_colony_d.pen_id', $this->pen_id);
            })
            ->with([
                'treatmentH',
                'pen',
                'disease',
                'treatmentColonyMedicineItems',
                'treatmentColonyTreatmentItems'
            ])
            ->orderBy('treatment_h.transaction_date', 'desc')
            ->paginate(10);
    }

    public function getStatisticsProperty()
    {
        if (!$this->showReport) {
            return [];
        }

        $count = TreatmentColonyD::join('treatment_h', 'treatment_colony_d.treatment_h_id', '=', 'treatment_h.id')
            ->where('treatment_h.farm_id', $this->farm->id)
            ->where('treatment_h.type', 'colony')
            ->whereBetween('treatment_h.transaction_date', [$this->start_date, $this->end_date])
            ->when($this->pen_id, function ($q) {
                $q->where('treatment_colony_d.pen_id', $this->pen_id);
            })
            ->count();

        return [
            'total_treatments' => $count,
        ];
    }

    public function render()
    {
        return view('livewire.reports.care-livestock.treatment-colony-report.index', [
            'pens' => $this->farm->pens,
            'treatments' => $this->treatmentData,
            'stats' => $this->statistics,
        ])
            ->extends('layouts.care_livestock.index')
            ->section('content');
    }
}
