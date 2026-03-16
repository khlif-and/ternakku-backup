<?php

namespace App\Services\Web\Report\NaturalInsemination\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Farm;
use App\Services\Web\Report\NaturalInsemination\Services\NaturalInseminationReportService;
use Carbon\Carbon;

class NaturalInseminationReportController extends Controller
{
    public function exportPdf(Request $request, $farmId)
    {
        $farm = Farm::findOrFail($farmId);
        $service = new NaturalInseminationReportService();

        $filters = $request->all();
        // Default dates if not provided
        if (empty($filters['start_date'])) {
            $filters['start_date'] = Carbon::now()->startOfMonth()->format('Y-m-d');
        }
        if (empty($filters['end_date'])) {
            $filters['end_date'] = Carbon::now()->endOfMonth()->format('Y-m-d');
        }

        $data = $service->generateReport($farmId, $filters);

        $pdf = Pdf::loadView('pdf.care_livestock.natural_insemination.export', [
            'data' => $data,
            'farm' => $farm,
            'start_date' => $filters['start_date'],
            'end_date' => $filters['end_date'],
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('laporan-inseminasi-alami.pdf');
    }
}
