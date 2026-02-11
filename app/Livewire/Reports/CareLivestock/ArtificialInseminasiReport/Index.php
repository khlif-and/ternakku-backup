<?php

namespace App\Livewire\Reports\CareLivestock\ArtificialInseminasiReport;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Farm;
use App\Models\Pen;
use App\Models\InseminationArtificial;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class Index extends Component
{
    use WithPagination;

    public Farm $farm;
    public $pen_id = '';
    public $start_date;
    public $end_date;
    public $showReport = false;

    // Statistics
    public $statistics = [];

    protected $queryString = ['pen_id', 'start_date', 'end_date'];

    protected $rules = [
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
    ];

    protected $messages = [
        'start_date.required' => 'Tanggal mulai wajib diisi.',
        'end_date.required' => 'Tanggal akhir wajib diisi.',
        'end_date.after_or_equal' => 'Tanggal akhir harus setelah tanggal mulai.',
    ];

    public function mount(Farm $farm)
    {
        $this->farm = $farm;
        $this->start_date = request('start_date', now()->subMonth()->format('Y-m-d'));
        $this->end_date = request('end_date', now()->format('Y-m-d'));

        if (request('pen_id') || request('start_date')) {
            $this->generateReport();
        }
    }

    public function generateReport()
    {
        $this->validate();
        $this->showReport = true;
        $this->resetPage();
    }

    public function getArtificialInseminationsProperty()
    {
        if (!$this->showReport) {
            return [];
        }

        return InseminationArtificial::with([
            'insemination',
            'reproductionCycle.livestock.livestockType',
            'reproductionCycle.livestock.livestockBreed',
            'reproductionCycle.livestock.pen',
        ])
            ->whereHas('insemination', function ($q) {
            $q->where('farm_id', $this->farm->id)
                ->where('type', 'artificial')
                ->whereBetween('date', [$this->start_date, $this->end_date]);
        })
            ->when($this->pen_id, function ($q) {
            $q->whereHas('reproductionCycle.livestock', function ($sq) {
                    $sq->where('pen_id', $this->pen_id);
                }
                );
            })
            ->orderByDesc('id')
            ->paginate(10);
    }

    public function getStatisticsProperty()
    {
        if (!$this->showReport) {
            return [];
        }

        // We need a separate query for stats to avoid pagination limits
        $query = InseminationArtificial::whereHas('insemination', function ($q) {
            $q->where('farm_id', $this->farm->id)
                ->where('type', 'artificial')
                ->whereBetween('date', [$this->start_date, $this->end_date]);
        })
            ->when($this->pen_id, function ($q) {
            $q->whereHas('reproductionCycle.livestock', function ($sq) {
                    $sq->where('pen_id', $this->pen_id);
                }
                );
            });

        $total = $query->count();

        // Example stats: Success rate could be calculated if we check pregnancy status later.
        // For now, let's just count total IB.

        return [
            'total_ib' => $total,
        ];
    }

    public function render()
    {
        return view('livewire.reports.care-livestock.artificial-inseminasi-report.index', [
            'pens' => $this->farm->pens()->orderBy('name')->get(),
            'inseminations' => $this->artificialInseminations,
            'stats' => $this->statistics,
        ])
            ->extends('layouts.care_livestock.index')
            ->section('content');
    }
}
