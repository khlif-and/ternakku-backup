<?php

namespace App\Services\Web\Report\QurbanPayment\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Farm;
use App\Services\Web\Report\QurbanPayment\Services\QurbanPaymentReportService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class QurbanPaymentReportController extends Controller
{
    protected $service;

    public function __construct(QurbanPaymentReportService $service)
    {
        $this->service = $service;
    }

    public function exportPdf(Request $request, $farmId)
    {
        $farm = Farm::findOrFail($farmId);
        $filters = [
            'start_date' => $request->start_date ?? now()->startOfMonth()->format('Y-m-d'),
            'end_date' => $request->end_date ?? now()->endOfMonth()->format('Y-m-d'),
            'qurban_customer_id' => $request->qurban_customer_id,
        ];

        $data = $this->service->generateReport($farm->id, $filters);
        $summary = $this->service->getSummary($farm->id, $filters);

        $pdf = Pdf::loadView('pdf.care_livestock.qurban_payment.export', [
            'data' => $data,
            'summary' => $summary,
            'farm' => $farm,
            'start_date' => $filters['start_date'],
            'end_date' => $filters['end_date'],
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('laporan-penerimaan-pembayaran-qurban-' . now()->format('Y-m-d') . '.pdf');
    }
}
