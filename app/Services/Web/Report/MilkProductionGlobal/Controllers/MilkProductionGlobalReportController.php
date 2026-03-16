<?php

namespace App\Services\Web\Report\MilkProductionGlobal\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Farm;
use App\Services\Web\Report\MilkProductionGlobal\Resources\MilkProductionGlobalReportResource;
use App\Services\Web\Report\MilkProductionGlobal\Services\MilkProductionGlobalReportService;
use Illuminate\Http\Request;
use PDF;

class MilkProductionGlobalReportController extends Controller
{
    protected $service;

    public function __construct(MilkProductionGlobalReportService $service)
    {
        $this->service = $service;
    }

    public function exportPdf(Request $request, $farm_id)
    {
        $farm = Farm::findOrFail($farm_id);
        $filters = $request->only(['start_date', 'end_date']);

        $data = $this->service->getAll($farm->id, $filters);
        $summary = $this->service->getSummary($farm->id, $filters);

        $pdf = \PDF::loadView('pdf.care_livestock.milk_production_global.export', [
            'data' => MilkProductionGlobalReportResource::collection($data)->resolve(),
            'farm' => $farm,
            'summary' => $summary,
            'start_date' => $request->start_date ?? now()->format('Y-m-d'),
            'end_date' => $request->end_date ?? now()->format('Y-m-d'),
            'type' => 'all'
        ]);

        return $pdf->stream('Laporan-Produksi-Susu-Global-' . now()->format('YmdHis') . '.pdf');
    }

    public function exportRowPdf(Request $request, $farm_id, $id)
    {
        $farm = Farm::findOrFail($farm_id);
        $item = $this->service->find($farm->id, $id);

        $data = [new MilkProductionGlobalReportResource($item)];

        $pdf = \PDF::loadView('pdf.care_livestock.milk_production_global.export', [
            'data' => collect($data)->map(fn($r) => $r->resolve())->first(),
            'farm' => $farm,
            'type' => 'single'
        ]);

        return $pdf->stream('Laporan-Produksi-Susu-Global-Row-' . $id . '-' . now()->format('YmdHis') . '.pdf');
    }
}
