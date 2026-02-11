<?php

namespace App\Livewire\Reports\CareLivestock\PregnantCheckReport;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Farm;
use App\Models\PregnantCheckD;
use Illuminate\Support\Facades\Log;

class Index extends Component
{
    use WithPagination;

    public Farm $farm;
    public $start_date;
    public $end_date;
    public $pen_id = '';
    public $livestock_id = '';
    public $status = '';
    public $showReport = false;

    public $statistics = [];

    protected $queryString = ['start_date', 'end_date', 'pen_id', 'livestock_id', 'status'];

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

    public function getChecksProperty()
    {
        if (!$this->showReport) {
            return [];
        }

        return PregnantCheckD::with([
            'pregnantCheck',
            'reproductionCycle.livestock.livestockType',
            'reproductionCycle.livestock.pen'
        ])
            ->whereHas('pregnantCheck', function ($q) {
            $q->where('farm_id', $this->farm->id)
                ->whereBetween('transaction_date', [$this->start_date, $this->end_date]);
        })
            ->when($this->pen_id, function ($q) {
            $q->whereHas('reproductionCycle.livestock', function ($sq) {
                    $sq->where('pen_id', $this->pen_id);
                }
                );
            })
            ->when($this->livestock_id, function ($q) {
            // Assuming livestock_id is on the reproduction cycle or we need to look it up through it
            $q->whereHas('reproductionCycle', function ($sq) {
                    $sq->where('livestock_id', $this->livestock_id);
                }
                );
            })
            ->when($this->status, function ($q) {
            $q->where('status', $this->status);
        })
            ->orderByDesc('id')
            ->paginate(10);
    }

    public function getStatisticsProperty()
    {
        if (!$this->showReport) {
            return [];
        }

        $query = PregnantCheckD::whereHas('pregnantCheck', function ($q) {
            $q->where('farm_id', $this->farm->id)
                ->whereBetween('transaction_date', [$this->start_date, $this->end_date]);
        })
            ->when($this->pen_id, function ($q) {
            $q->whereHas('reproductionCycle.livestock', function ($sq) {
                    $sq->where('pen_id', $this->pen_id);
                }
                );
            })
            ->when($this->livestock_id, function ($q) {
            $q->whereHas('reproductionCycle', function ($sq) {
                    $sq->where('livestock_id', $this->livestock_id);
                }
                );
            })
            ->when($this->status, function ($q) {
            $q->where('status', $this->status);
        });

        $totalChecks = $query->count();
        $totalPregnant = (clone $query)->where('status', 'PREGNANT')->count();
        $totalNotPregnant = (clone $query)->where('status', '!=', 'PREGNANT')->count();

        return [
            'total_checks' => $totalChecks,
            'total_pregnant' => $totalPregnant,
            'total_not_pregnant' => $totalNotPregnant,
        ];
    }

    public function render()
    {
        return view('livewire.reports.care-livestock.pregnant-check-report.index', [
            'pens' => $this->farm->pens()->orderBy('name')->get(),
            // Get female livestocks for filter
            'livestocks' => $this->farm->livestocks()
            ->whereHas('livestockSex', fn($q) => $q->whereRaw('LOWER(name) = ?', ['betina']))
            ->orderBy('eartag_number')
            ->get(),
            'checks' => $this->checks,
            'stats' => $this->statistics,
        ])
            ->extends('layouts.care_livestock.index')
            ->section('content');
    }
}
