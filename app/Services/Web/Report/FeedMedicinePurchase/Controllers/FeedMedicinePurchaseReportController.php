<?php

namespace App\Services\Web\Report\FeedMedicinePurchase\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Farm;
use App\Services\Web\Report\FeedMedicinePurchase\Services\FeedMedicinePurchaseReportService;
use Carbon\Carbon;

class FeedMedicinePurchaseReportController extends Controller
{
    public function exportPdf(Request $request, $farmId)
    {
        $farm = Farm::findOrFail($farmId);
        $service = new FeedMedicinePurchaseReportService();

        $filters = $request->all();
        // Default dates if not provided
        if (empty($filters['start_date'])) {
            $filters['start_date'] = Carbon::now()->startOfMonth()->format('Y-m-d');
        }
        if (empty($filters['end_date'])) {
            $filters['end_date'] = Carbon::now()->endOfMonth()->format('Y-m-d');
        }

        $data = $service->generateReport($farmId, $filters);

        $pdf = Pdf::loadView('pdf.care_livestock.feed_medicine_purchase.export', [
            'data' => $data,
            'farm' => $farm,
            'start_date' => $filters['start_date'],
            'end_date' => $filters['end_date'],
            'purchase_type' => $filters['purchase_type'] ?? null,
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('laporan-pembelian-pakan-obat.pdf');
    }
}
