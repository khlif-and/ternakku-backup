<?php

namespace App\Services\Web\Report\QurbanReception\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Farm;
use App\Services\Web\Report\QurbanReception\Services\QurbanReceptionReportService;
use App\Services\Web\Report\QurbanReception\Resources\QurbanReceptionReportResource;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class QurbanReceptionReportController extends Controller
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
            'supplier' => $request->query('supplier'),
            'livestock_type_id' => $request->query('livestock_type_id'),
        ];

        $service = new QurbanReceptionReportService();
        $data = $service->generateReport($farm->id, $filters);

        $pdf = Pdf::loadView('livewire.reports.qurban.reception-report.pdf', [
            'data' => QurbanReceptionReportResource::collection($data),
            'farm' => $farm,
            'start_date' => Carbon::parse($filters['start_date'])->format('d M Y'),
            'end_date' => Carbon::parse($filters['end_date'])->format('d M Y'),
        ]);

        return $pdf->download('laporan-penerimaan-hewan-qurban-' . Carbon::now()->format('YmdHis') . '.pdf');
    }
}
