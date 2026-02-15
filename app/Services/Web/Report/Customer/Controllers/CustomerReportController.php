<?php

namespace App\Services\Web\Report\Customer\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Farm;
use App\Services\Web\Report\Customer\Services\CustomerReportService;
use Barryvdh\DomPDF\Facade\Pdf;

class CustomerReportController extends Controller
{
    public function exportPdf(Request $request, $farm_id)
    {
        $farm = Farm::findOrFail($farm_id);
        $service = new CustomerReportService();
        $filters = $request->all();

        $query = $service->getQuery($farm_id, $filters);
        $data = $query->get();

        $pdf = Pdf::loadView('pdf.care_livestock.customer.export', [
            'data' => $data,
            'farm' => $farm,
        ]);

        return $pdf->stream('laporan-customer.pdf');
    }
}
