<?php

namespace App\Services\Web\Report\SalesOrder\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Farm;
use App\Services\Web\Report\SalesOrder\Services\SalesOrderReportService;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class SalesOrderReportController extends Controller
{
    public function index(Request $request, $farm_id)
    {
        $service = new SalesOrderReportService();
        $filters = $request->all();

        $query = $service->getQuery($farm_id, $filters);

        // Pagination logic if needed, or just get all for report
        $perPage = $request->input('per_page', 10);
        $data = $query->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function exportPdf(Request $request, $farm_id)
    {
        $farm = Farm::findOrFail($farm_id);
        $service = new SalesOrderReportService();
        $filters = $request->all();

        $query = $service->getQuery($farm_id, $filters);
        $data = $query->get();

        $start_date = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $end_date = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $pdf = Pdf::loadView('pdf.care_livestock.sales_order.export', [
            'data' => $data,
            'farm' => $farm,
            'start_date' => $start_date,
            'end_date' => $end_date,
        ]);

        return $pdf->stream('laporan-sales-order.pdf');
    }
}
