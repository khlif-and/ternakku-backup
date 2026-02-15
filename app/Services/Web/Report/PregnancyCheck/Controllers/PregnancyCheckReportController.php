<?php

namespace App\Services\Web\Report\PregnancyCheck\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Farm;
use App\Services\Web\Report\PregnancyCheck\Services\PregnancyCheckReportService;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class PregnancyCheckReportController extends Controller
{
    public function exportPdf(Request $request, $farm_id)
    {
        $farm = Farm::findOrFail($farm_id);
        $service = new PregnancyCheckReportService();
        $filters = $request->all();

        $query = $service->getQuery($farm_id, $filters);
        $data = $query->get();

        $start_date = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $end_date = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $pdf = Pdf::loadView('pdf.care_livestock.pregnancy_check.export', [
            'data' => $data,
            'farm' => $farm,
            'start_date' => $start_date,
            'end_date' => $end_date,
        ]);

        return $pdf->stream('laporan-cek-kehamilan.pdf');
    }
}
