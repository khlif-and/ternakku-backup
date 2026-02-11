<?php

namespace App\Livewire\Reports\CareLivestock\MutationIndividuReport;

use Livewire\Component;
use App\Models\Farm;
use App\Models\Pen;
use App\Models\Livestock;
use App\Models\MutationIndividuD;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class Index extends Component
{
    public Farm $farm;
    public $pen_id = '';
    public $start_date;
    public $end_date;
    public $showReport = false;

    // Report Data
    public $mutations = [];
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
        $this->start_date = now()->subMonth()->format('Y-m-d');
        $this->end_date = now()->format('Y-m-d');
    }

    public function generateReport()
    {
        $this->validate();

        try {
            $query = MutationIndividuD::with(['mutationH', 'livestock', 'fromPen', 'toPen'])
                ->whereHas('mutationH', function ($q) {
                $q->where('farm_id', $this->farm->id)
                    ->where('type', 'individu')
                    ->whereBetween('transaction_date', [$this->start_date, $this->end_date]);
            });

            if ($this->pen_id) {
                $query->where(function ($q) {
                    $q->where('from', $this->pen_id)
                        ->orWhere('to', $this->pen_id);
                });
            }

            $this->mutations = $query->orderByDesc('id')->get();

            // Calculate statistics
            $this->statistics = $this->calculateStatistics();

            $this->showReport = true;

        }
        catch (\Throwable $e) {
            Log::error('Mutation Individu Report Error', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);
            session()->flash('error', 'Gagal memuat laporan: ' . $e->getMessage());
        }
    }

    public function resetReport()
    {
        $this->showReport = false;
        $this->pen_id = '';
        $this->mutations = [];
        $this->statistics = [];
    }

    private function calculateStatistics(): array
    {
        $totalMutations = $this->mutations->count();

        // Count distinct livestock involved
        $totalLivestockInvolved = $this->mutations->pluck('livestock_id')->unique()->count();

        // Count by mutation type (implied by movement)
        // Since we filtered by 'individu' type in header, we can look at flow
        // But for specific pen context, it's In/Out. 
        // For farm context, it's mostly internal movement unless we have external.

        return [
            'total_mutations' => $totalMutations,
            'total_livestock_involved' => $totalLivestockInvolved,
        ];
    }

    public function render()
    {
        return view('livewire.reports.care-livestock.mutation-individu-report.index', [
            'pens' => $this->farm->pens()->orderBy('name')->get(),
        ])
            ->extends('layouts.care_livestock.index')
            ->section('content');
    }
}
