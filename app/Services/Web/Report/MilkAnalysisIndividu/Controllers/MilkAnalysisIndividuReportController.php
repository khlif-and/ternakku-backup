<?php

namespace App\Services\Web\Report\MilkAnalysisIndividu\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Farm;
use App\Services\Web\Report\MilkAnalysisIndividu\Services\MilkAnalysisIndividuReportService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class MilkAnalysisIndividuReportController extends Controller
{
    protected $service;

    public function __construct(MilkAnalysisIndividuReportService $service)
    {
        $this->service = $service;
    }

    public function exportPdf(Request $request, $farmId)
    {
        $farm = Farm::findOrFail($farmId);
        $filters = [
            'start_date' => $request->start_date ?? now()->format('Y-m-d'),
            'end_date' => $request->end_date ?? now()->format('Y-m-d'),
            'livestock_id' => $request->livestock_id,
        ];

        $data = $this->service->generateReport($farm->id, $filters);
        $summary = $this->service->getSummary($farm->id, $filters);

        $pdf = Pdf::loadView('pdf.care_livestock.milk_analysis_individu.export', [
            'data' => $data,
            'summary' => $summary,
            'farm' => $farm,
            'start_date' => $filters['start_date'],
            'end_date' => $filters['end_date'],
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('laporan-analisis-susu-individu-' . now()->format('Y-m-d') . '.pdf');
    }
}
