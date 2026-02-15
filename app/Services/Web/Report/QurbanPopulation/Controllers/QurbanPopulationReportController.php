<?php

namespace App\Services\Web\Report\QurbanPopulation\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Farm;
use App\Services\Web\Report\QurbanPopulation\Services\QurbanPopulationReportService;
use Barryvdh\DomPDF\Facade\Pdf;

class QurbanPopulationReportController extends Controller
{
    public function exportPdf(Request $request, $farm_id)
    {
        $farm = Farm::findOrFail($farm_id);
        $service = new QurbanPopulationReportService();
        $filters = $request->all();

        $query = $service->getQuery($farm_id, $filters);
        $data = $query->get();

        $pdf = Pdf::loadView('pdf.care_livestock.qurban_population.export', [
            'data' => $data,
            'farm' => $farm,
        ]);

        return $pdf->stream('laporan-populasi-qurban.pdf');
    }
}
