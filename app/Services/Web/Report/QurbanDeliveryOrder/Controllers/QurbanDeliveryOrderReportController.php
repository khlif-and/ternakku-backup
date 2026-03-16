<?php

namespace App\Services\Web\Report\QurbanDeliveryOrder\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Web\Report\QurbanDeliveryOrder\Services\QurbanDeliveryOrderReportService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Farm;
use Carbon\Carbon;

class QurbanDeliveryOrderReportController extends Controller
{
    protected $service;

    public function __construct(QurbanDeliveryOrderReportService $service)
    {
        $this->service = $service;
    }

    public function exportPdf(Request $request, $farmId)
    {
        $farm = Farm::find($farmId);
        $request->merge(['export' => true]);
        $data = $this->service->getReportData($request, $farmId);

        $pdf = Pdf::loadView('pdf.care_livestock.qurban_delivery_order.export', [
            'data' => $data,
            'farm' => $farm,
            'start_date' => $request->start_date ? Carbon::parse($request->start_date)->translatedFormat('d F Y') : null,
            'end_date' => $request->end_date ? Carbon::parse($request->end_date)->translatedFormat('d F Y') : null,
            'user' => auth()->user(),
            'generated_at' => now()->translatedFormat('d F Y H:i'),
        ]);

        return $pdf->stream('Laporan-Surat-Jalan-Qurban.pdf');
    }
}
