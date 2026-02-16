<?php

namespace App\Services\Web\Report\QurbanDelivery\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Farm;
use App\Services\Web\Report\QurbanDelivery\Services\QurbanDeliveryReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class QurbanDeliveryReportController extends Controller
{
    public function exportPdf(Request $request)
    {
        $farmId = $request->query('farm_id');

        if (!$farmId) {
            $farmId = session('selected_farm');
        }

        if (!$farmId) {
            abort(404, 'Farm Selection Required');
        }

        $farm = Farm::findOrFail($farmId);

        $filters = [
            'start_date' => $request->query('start_date', Carbon::now()->startOfMonth()->format('Y-m-d')),
            'end_date' => $request->query('end_date', Carbon::now()->endOfMonth()->format('Y-m-d')),
            'driver_id' => $request->query('driver_id'),
            'fleet_id' => $request->query('fleet_id'),
            'status' => $request->query('status'),
        ];

        $service = new QurbanDeliveryReportService();
        $data = $service->generateReport($farm->id, $filters);

        $pdf = Pdf::loadView('livewire.reports.qurban.delivery-report.pdf', [
            'data' => $data,
            'farm' => $farm,
            'start_date' => Carbon::parse($filters['start_date'])->format('d M Y'),
            'end_date' => Carbon::parse($filters['end_date'])->format('d M Y'),
        ]);

        return $pdf->download('laporan-pengiriman-hewan-qurban-' . Carbon::now()->format('YmdHis') . '.pdf');
    }
}
