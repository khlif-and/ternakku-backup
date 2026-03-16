<?php

namespace App\Services\Web\Report\MilkAnalysisGlobal\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Farm;
use App\Services\Web\Report\MilkAnalysisGlobal\Requests\MilkAnalysisGlobalReportRequest;
use App\Services\Web\Report\MilkAnalysisGlobal\Services\MilkAnalysisGlobalReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class MilkAnalysisGlobalReportController extends Controller
{
    protected $service;

    public function __construct(MilkAnalysisGlobalReportService $service)
    {
        $this->service = $service;
    }

    public function exportPdf(MilkAnalysisGlobalReportRequest $request, Farm $farm)
    {
        $validated = $request->validated();
        $data = $this->service->generateReport($farm, $validated);

        $pdf = Pdf::loadView('pdf.care_livestock.milk_analysis_global.export', [
            'data' => $data,
            'farm' => $farm,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
        ]);

        return $pdf->stream('laporan-analisis-susu-global.pdf');
    }
}
