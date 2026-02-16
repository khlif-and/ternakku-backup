<?php

namespace App\Services\Web\Report\QurbanCancelation\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Farm;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\Web\Report\QurbanCancelation\Services\QurbanCancelationReportService;

class QurbanCancelationReportController extends Controller
{
    protected $service;

    public function __construct(QurbanCancelationReportService $service)
    {
        $this->service = $service;
    }

    public function exportPdf(Request $request, $farmId)
    {
        $farm = Farm::findOrFail($farmId);

        $filters = [
            'start_date' => $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d')),
            'end_date' => $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d')),
            'qurban_customer_id' => $request->input('qurban_customer_id'),
        ];

        $data = $this->service->generateReport($farmId, $filters);
        $summary = $this->service->getSummary($farmId, $filters);

        $pdf = Pdf::loadView('pdf.care_livestock.qurban_cancelation.export', [
            'data' => $data,
            'summary' => $summary,
            'farm' => $farm,
            'filters' => $filters,
        ]);

        return $pdf->stream('laporan-pembatalan-qurban.pdf');
    }
}
