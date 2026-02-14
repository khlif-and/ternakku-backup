<?php

namespace App\Services\Web\Report\MilkProductionIndividu\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Farm;
use App\Services\Web\Report\MilkProductionIndividu\Resources\MilkProductionIndividuReportResource;
use App\Services\Web\Report\MilkProductionIndividu\Services\MilkProductionIndividuReportService;
use Illuminate\Http\Request;
use PDF;

class MilkProductionIndividuReportController extends Controller
{
    protected $service;

    public function __construct(MilkProductionIndividuReportService $service)
    {
        $this->service = $service;
    }

    public function exportPdf(Request $request, $farm_id)
    {
        $farm = Farm::findOrFail($farm_id);
        $filters = $request->only(['start_date', 'end_date']);

        $data = $this->service->getAll($farm->id, $filters);
        $summary = $this->service->getSummary($farm->id, $filters);

        $pdf = \PDF::loadView('pdf.care_livestock.milk_production_individu.export', [
            'data' => MilkProductionIndividuReportResource::collection($data)->resolve(),
            'farm' => $farm,
            'summary' => $summary,
            'start_date' => $request->start_date ?? now()->format('Y-m-d'),
            'end_date' => $request->end_date ?? now()->format('Y-m-d'),
            'type' => 'all'
        ]);

        return $pdf->stream('Laporan-Produksi-Susu-Individu-' . now()->format('YmdHis') . '.pdf');
    }

    public function exportRowPdf(Request $request, $farm_id, $id)
    {
        $farm = Farm::findOrFail($farm_id);
        $item = $this->service->find($farm->id, $id);

        $data = [new MilkProductionIndividuReportResource($item)];

        $pdf = \PDF::loadView('pdf.care_livestock.milk_production_individu.export', [
            'data' => collect($data)->map(fn($r) => $r->resolve())->first(),
            'farm' => $farm,
            'type' => 'single'
        ]);

        return $pdf->stream('Laporan-Produksi-Susu-Individu-Row-' . $id . '-' . now()->format('YmdHis') . '.pdf');
    }
}
