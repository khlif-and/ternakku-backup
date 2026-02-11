<?php

namespace App\Livewire\Reports\CareLivestock\BirthReport;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Farm;
use App\Models\LivestockBirth;
use Illuminate\Support\Facades\Log;

class Index extends Component
{
    use WithPagination;

    public Farm $farm;
    public $start_date;
    public $end_date;
    public $pen_id = '';
    public $livestock_id = ''; // Mother livestock
    public $showReport = false;

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

    public function getBirthsProperty()
    {
        if (!$this->showReport) {
            return [];
        }

        return LivestockBirth::with([
            'livestockBirthD', // Offspring details
            'reproductionCycle.livestock.livestockType',
            'reproductionCycle.livestock.pen'
        ])
            ->where('farm_id', $this->farm->id)
            ->whereBetween('transaction_date', [$this->start_date, $this->end_date])
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
            ->orderByDesc('transaction_date')
            ->orderByDesc('id')
            ->paginate(10);
    }

    public function getStatisticsProperty()
    {
        if (!$this->showReport) {
            return [];
        }

        $query = LivestockBirth::where('farm_id', $this->farm->id)
            ->whereBetween('transaction_date', [$this->start_date, $this->end_date])
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
            });

        $totalBirths = $query->count();

        // Count offspring from nested relation
        // We need to use withCount or join to count details efficiently, but for now retrieving ids and counting D is safer for complex relation scopes if performance isn't critical yet.
        // Or better: aggregate queries.

        $birthIds = $query->pluck('id');

        $totalOffspring = \App\Models\LivestockBirthD::whereIn('livestock_birth_id', $birthIds)->count();
        $aliveOffspring = \App\Models\LivestockBirthD::whereIn('livestock_birth_id', $birthIds)->where('status', 'alive')->count();
        $deadOffspring = \App\Models\LivestockBirthD::whereIn('livestock_birth_id', $birthIds)->where('status', 'dead')->count();

        return [
            'total_birth_events' => $totalBirths,
            'total_offspring' => $totalOffspring,
            'alive_offspring' => $aliveOffspring,
            'dead_offspring' => $deadOffspring,
        ];
    }

    public function render()
    {
        return view('livewire.reports.care-livestock.birth-report.index', [
            'pens' => $this->farm->pens()->orderBy('name')->get(),
            // Get female livestocks (mothers) for filter
            'livestocks' => $this->farm->livestocks()
            ->whereHas('livestockSex', fn($q) => $q->whereRaw('LOWER(name) = ?', ['betina']))
            ->orderBy('eartag_number')
            ->get(),
            'births' => $this->births,
            'stats' => $this->statistics,
        ])
            ->extends('layouts.care_livestock.index')
            ->section('content');
    }
}
