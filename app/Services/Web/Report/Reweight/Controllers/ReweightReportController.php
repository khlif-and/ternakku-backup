<?php

namespace App\Services\Web\Report\Reweight\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Farm;
use App\Services\Web\Report\Reweight\Services\ReweightReportService;
use Carbon\Carbon;

class ReweightReportController extends Controller
{
    public function exportPdf(Request $request, $farmId)
    {
        $farm = Farm::findOrFail($farmId);
        $service = new ReweightReportService();

        $filters = $request->all();
        // Default dates if not provided
        if (empty($filters['start_date'])) {
            $filters['start_date'] = Carbon::now()->startOfMonth()->format('Y-m-d');
        }
        if (empty($filters['end_date'])) {
            $filters['end_date'] = Carbon::now()->endOfMonth()->format('Y-m-d');
        }

        $data = $service->generateReport($farmId, $filters);

        $pdf = Pdf::loadView('pdf.care_livestock.reweight.export', [
            'data' => $data,
            'farm' => $farm,
            'start_date' => $filters['start_date'],
            'end_date' => $filters['end_date'],
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('laporan-timbang-ulang.pdf');
    }
}
