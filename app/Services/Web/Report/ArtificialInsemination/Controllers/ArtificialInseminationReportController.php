<?php

namespace App\Services\Web\Report\ArtificialInsemination\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Farm;
use App\Services\Web\Report\ArtificialInsemination\Services\ArtificialInseminationReportService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ArtificialInseminationReportController extends Controller
{
    protected $service;

    public function __construct(ArtificialInseminationReportService $service)
    {
        $this->service = $service;
    }

    public function exportPdf(Request $request, $farmId)
    {
        $farm = Farm::findOrFail($farmId);
        $filters = $request->except(['page']);

        $data = $this->service->generateReport($farm->id, $filters);

        $pdf = Pdf::loadView('pdf.care_livestock.artificial_insemination.export', [
            'data' => $data,
            'farm' => $farm,
            'filters' => $filters
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('laporan-ib-' . now()->format('Y-m-d') . '.pdf');
    }
}
