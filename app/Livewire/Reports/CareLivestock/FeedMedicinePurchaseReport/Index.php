<?php

namespace App\Livewire\Reports\CareLivestock\FeedMedicinePurchaseReport;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Farm;
use App\Models\FeedMedicinePurchase;
use Illuminate\Support\Facades\Log;

class Index extends Component
{
    use WithPagination;

    public Farm $farm;
    public $start_date;
    public $end_date;
    public $purchase_type = '';
    public $showReport = false;

    public $statistics = [];

    protected $queryString = ['start_date', 'end_date', 'purchase_type'];

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

    public function getPurchasesProperty()
    {
        if (!$this->showReport) {
            return [];
        }

        return FeedMedicinePurchase::with(['feedMedicinePurchaseItem'])
            ->where('farm_id', $this->farm->id)
            ->whereBetween('transaction_date', [$this->start_date, $this->end_date])
            ->when($this->purchase_type, function ($q) {
            $q->whereHas('feedMedicinePurchaseItem', function ($sq) {
                    $sq->where('purchase_type', $this->purchase_type);
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

        $query = FeedMedicinePurchase::where('farm_id', $this->farm->id)
            ->whereBetween('transaction_date', [$this->start_date, $this->end_date])
            ->when($this->purchase_type, function ($q) {
            $q->whereHas('feedMedicinePurchaseItem', function ($sq) {
                    $sq->where('purchase_type', $this->purchase_type);
                }
                );
            });

        $totalTransactions = $query->count();
        $totalAmount = $query->sum('total_amount');

        return [
            'total_transactions' => $totalTransactions,
            'total_amount' => $totalAmount,
        ];
    }

    public function render()
    {
        return view('livewire.reports.care-livestock.feed-medicine-purchase-report.index', [
            'purchases' => $this->purchases,
            'stats' => $this->statistics,
        ])
            ->extends('layouts.care_livestock.index')
            ->section('content');
    }
}
