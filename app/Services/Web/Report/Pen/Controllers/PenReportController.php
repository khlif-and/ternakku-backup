<?php

namespace App\Services\Web\Report\Pen\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Farm;
use App\Services\Web\Report\Pen\Services\PenReportService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PenReportController extends Controller
{
    protected $service;

    public function __construct(PenReportService $service)
    {
        $this->service = $service;
    }

    public function exportPdf(Request $request, $farmId)
    {
        $farm = Farm::findOrFail($farmId);
        $filters = [
            'search' => $request->search,
        ];

        $data = $this->service->generateReport($farm->id, $filters);
        $summary = $this->service->getSummary($farm->id, $filters);

        $pdf = Pdf::loadView('pdf.care_livestock.pen.export', [
            'data' => $data,
            'summary' => $summary,
            'farm' => $farm,
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('laporan-kandang-' . now()->format('Y-m-d') . '.pdf');
    }
}
