<?php

namespace App\Livewire\Reports\CareLivestock\NaturalInseminasiReport;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Farm;
use App\Models\InseminationNatural;
use App\Models\LivestockBreed;
use Illuminate\Support\Facades\Log;

class Index extends Component
{
    use WithPagination;

    public Farm $farm;
    public $start_date;
    public $end_date;
    public $pen_id = '';
    public $livestock_id = '';
    public $showReport = false;

    // Statistics
    public $statistics = [];

    protected $queryString = ['start_date', 'end_date', 'pen_id', 'livestock_id'];

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

        if (request('start_date')) {
            $this->generateReport();
        }
    }

    public function generateReport()
    {
        $this->validate();
        $this->showReport = true;
        $this->resetPage();
    }

    public function getNaturalInseminationsProperty()
    {
        if (!$this->showReport) {
            return [];
        }

        return InseminationNatural::with([
            'insemination',
            'reproductionCycle.livestock.livestockType',
            'reproductionCycle.livestock.livestockBreed',
            'reproductionCycle.livestock.pen',
        ])
            ->whereHas('insemination', function ($q) {
            $q->where('farm_id', $this->farm->id)
                ->where('type', 'natural')
                ->whereBetween('date', [$this->start_date, $this->end_date]);
        })
            ->when($this->pen_id, function ($q) {
            $q->whereHas('reproductionCycle.livestock', function ($sq) {
                    $sq->where('pen_id', $this->pen_id);
                }
                );
            })
            ->when($this->livestock_id, function ($q) {
            $q->whereHas('reproductionCycle.livestock', function ($sq) {
                    $sq->where('id', $this->livestock_id);
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

        $query = InseminationNatural::whereHas('insemination', function ($q) {
            $q->where('farm_id', $this->farm->id)
                ->where('type', 'natural')
                ->whereBetween('date', [$this->start_date, $this->end_date]);
        })
            ->when($this->pen_id, function ($q) {
            $q->whereHas('reproductionCycle.livestock', function ($sq) {
                    $sq->where('pen_id', $this->pen_id);
                }
                );
            })
            ->when($this->livestock_id, function ($q) {
            $q->whereHas('reproductionCycle.livestock', function ($sq) {
                    $sq->where('id', $this->livestock_id);
                }
                );
            });

        return [
            'total_natural' => $query->count(),
        ];
    }

    public function render()
    {
        return view('livewire.reports.care-livestock.natural-inseminasi-report.index', [
            'pens' => $this->farm->pens()->orderBy('name')->get(),
            // Get female livestocks for filter
            'livestocks' => $this->farm->livestocks()
            ->whereHas('livestockSex', fn($q) => $q->whereRaw('LOWER(name) = ?', ['betina']))
            ->orderBy('eartag_number')
            ->get(),
            'inseminations' => $this->naturalInseminations,
            'stats' => $this->statistics,
        ])
            ->extends('layouts.care_livestock.index')
            ->section('content');
    }
}
