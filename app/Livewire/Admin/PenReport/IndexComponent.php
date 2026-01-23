<?php

namespace App\Livewire\Admin\PenReport;

use Livewire\Component;
use App\Models\Farm;
use App\Models\Pen;
use App\Models\Livestock;
use App\Models\FeedingColonyD;
use App\Models\TreatmentColonyD;
use App\Models\MilkProductionColonyD;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class IndexComponent extends Component
{
    public Farm $farm;
    public $pen_id = '';
    public $from_date;
    public $to_date;
    public $showReport = false;
    
    // Report Data
    public $pen;
    public $livestocks = [];
    public $feedingHistory = [];
    public $treatmentHistory = [];
    public $milkProduction = [];
    public $statistics = [];

    protected $queryString = ['pen_id', 'from_date', 'to_date'];

    protected $rules = [
        'pen_id' => 'required|exists:pens,id',
        'from_date' => 'required|date',
        'to_date' => 'required|date|after_or_equal:from_date',
    ];

    protected $messages = [
        'pen_id.required' => 'Pilih kandang terlebih dahulu.',
        'from_date.required' => 'Tanggal mulai wajib diisi.',
        'to_date.required' => 'Tanggal akhir wajib diisi.',
        'to_date.after_or_equal' => 'Tanggal akhir harus setelah tanggal mulai.',
    ];

    public function mount(Farm $farm)
    {
        $this->farm = $farm;
        $this->from_date = now()->subMonth()->format('Y-m-d');
        $this->to_date = now()->format('Y-m-d');
    }

    public function generateReport()
    {
        $this->validate();

        try {
            $this->pen = Pen::with(['farm'])->findOrFail($this->pen_id);
            
            // Get livestocks in this pen
            $this->livestocks = Livestock::where('pen_id', $this->pen_id)
                ->with(['livestockType', 'livestockSex', 'livestockClassification'])
                ->get();

            // Get feeding history from FeedingColonyD (which has pen_id relation)
            $this->feedingHistory = FeedingColonyD::where('pen_id', $this->pen_id)
                ->whereHas('feedingH', function($q) {
                    $q->whereBetween('transaction_date', [$this->from_date, $this->to_date]);
                })
                ->with(['feedingH', 'feedingColonyItems.feed'])
                ->orderByDesc(function($q) {
                    return $q->select('transaction_date')
                        ->from('feeding_h')
                        ->whereColumn('feeding_h.id', 'feeding_colony_d.feeding_h_id')
                        ->limit(1);
                })
                ->limit(50)
                ->get();

            // Get treatment history from TreatmentColonyD
            $this->treatmentHistory = TreatmentColonyD::where('pen_id', $this->pen_id)
                ->whereHas('treatmentH', function($q) {
                    $q->whereBetween('transaction_date', [$this->from_date, $this->to_date]);
                })
                ->with(['treatmentH', 'treatmentColonyMedicineItems.medicine', 'disease'])
                ->orderByDesc(function($q) {
                    return $q->select('transaction_date')
                        ->from('treatment_h')
                        ->whereColumn('treatment_h.id', 'treatment_colony_d.treatment_h_id')
                        ->limit(1);
                })
                ->limit(50)
                ->get();

            // Get milk production from MilkProductionColonyD
            $this->milkProduction = MilkProductionColonyD::where('pen_id', $this->pen_id)
                ->whereHas('milkProductionH', function($q) {
                    $q->whereBetween('transaction_date', [$this->from_date, $this->to_date]);
                })
                ->with(['milkProductionH'])
                ->orderByDesc(function($q) {
                    return $q->select('transaction_date')
                        ->from('milk_production_h')
                        ->whereColumn('milk_production_h.id', 'milk_production_colony_d.milk_production_h_id')
                        ->limit(1);
                })
                ->limit(50)
                ->get();

            // Calculate statistics
            $this->statistics = $this->calculateStatistics();

            $this->showReport = true;
            
        } catch (\Throwable $e) {
            Log::error('Pen Report Error', [
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
        $this->pen = null;
        $this->livestocks = [];
        $this->feedingHistory = [];
        $this->treatmentHistory = [];
        $this->milkProduction = [];
        $this->statistics = [];
    }

    private function calculateStatistics(): array
    {
        $totalLivestock = $this->livestocks->count();
        $aliveLivestock = $this->livestocks->where('is_alive', true)->count();
        
        $maleCount = $this->livestocks->filter(function($l) {
            return strtolower($l->livestockSex->name ?? '') === 'jantan';
        })->count();
        
        $femaleCount = $this->livestocks->filter(function($l) {
            return strtolower($l->livestockSex->name ?? '') === 'betina';
        })->count();

        $totalFeedings = $this->feedingHistory->count();
        $totalTreatments = $this->treatmentHistory->count();
        
        $totalMilk = $this->milkProduction->sum('volume');
        $avgMilkPerDay = $this->milkProduction->count() > 0 
            ? round($totalMilk / $this->milkProduction->count(), 2) 
            : 0;

        // Group livestock by type
        $byType = $this->livestocks->groupBy(function($l) {
            return $l->livestockType->name ?? 'Lainnya';
        })->map->count();

        // Group by classification
        $byClassification = $this->livestocks->groupBy(function($l) {
            return $l->livestockClassification->name ?? 'Tidak diketahui';
        })->map->count();

        return [
            'total_livestock' => $totalLivestock,
            'alive_livestock' => $aliveLivestock,
            'male_count' => $maleCount,
            'female_count' => $femaleCount,
            'total_feedings' => $totalFeedings,
            'total_treatments' => $totalTreatments,
            'total_milk' => $totalMilk,
            'avg_milk_per_day' => $avgMilkPerDay,
            'by_type' => $byType,
            'by_classification' => $byClassification,
        ];
    }

    public function exportPdf()
    {
        $this->validate();

        try {
            // Regenerate data for PDF
            $this->generateReport();

            $pdf = Pdf::loadView('pdf.care_livestock.pen_report_full', [
                'farm' => $this->farm,
                'pen' => $this->pen,
                'livestocks' => $this->livestocks,
                'feedingHistory' => $this->feedingHistory,
                'treatmentHistory' => $this->treatmentHistory,
                'milkProduction' => $this->milkProduction,
                'statistics' => $this->statistics,
                'from_date' => $this->from_date,
                'to_date' => $this->to_date,
            ])->setPaper('A4', 'portrait');

            $filename = 'Laporan_Kandang_' . ($this->pen->name ?? 'Unknown') . '_' . now()->format('Ymd_His') . '.pdf';

            return response()->streamDownload(function () use ($pdf) {
                echo $pdf->output();
            }, $filename);

        } catch (\Throwable $e) {
            Log::error('Pen Report PDF Export Error', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);
            session()->flash('error', 'Gagal mengekspor PDF: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.pen-report.index-component', [
            'pens' => $this->farm->pens()->orderBy('name')->get(),
        ]);
    }
}
