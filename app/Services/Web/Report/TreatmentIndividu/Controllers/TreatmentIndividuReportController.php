<?php

namespace App\Services\Web\Report\TreatmentIndividu\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Web\Report\TreatmentIndividu\Services\TreatmentIndividuReportService;
use App\Services\Web\Report\TreatmentIndividu\Resources\TreatmentIndividuReportResource;
use App\Models\Farm;
use Illuminate\Http\Request;
use PDF;

class TreatmentIndividuReportController extends Controller
{
    protected $service;

    public function __construct(TreatmentIndividuReportService $service)
    {
        $this->service = $service;
    }

    public function index(Farm $farm, array $filters = [])
    {
        $data = $this->service->getReportData($farm->id, $filters);
        $summary = $this->service->getSummary($farm->id, $filters);

        return [
            'details' => TreatmentIndividuReportResource::collection($data),
            'summary' => $summary,
        ];
    }

    public function exportPdf(Request $request, $farm_id)
    {
        $farm = Farm::findOrFail($farm_id);
        $filters = $request->only(['start_date', 'end_date', 'pen_id']);

        $data = $this->service->getAll($farm->id, $filters);
        $summary = $this->service->getSummary($farm->id, $filters);

        $pdf = \PDF::loadView('pdf.care_livestock.treatment_individu.export', [
            'data' => TreatmentIndividuReportResource::collection($data)->resolve(),
            'farm' => $farm,
            'summary' => $summary,
            'filters' => $filters,
            'start_date' => $request->start_date ?? now()->format('Y-m-d'),
            'end_date' => $request->end_date ?? now()->format('Y-m-d'),
            'type' => 'all'
        ]);

        return $pdf->stream('Laporan-Perawatan-Individu-' . now()->format('YmdHis') . '.pdf');
    }

    public function exportRowPdf(Request $request, $farm_id, $id)
    {
        $farm = Farm::findOrFail($farm_id);
        $item = $this->service->find($farm->id, $id);

        $data = [new TreatmentIndividuReportResource($item)];

        $pdf = \PDF::loadView('pdf.care_livestock.treatment_individu.export', [
            'data' => collect($data)->map(fn($r) => $r->resolve())->first(),
            'farm' => $farm,
            'type' => 'single'
        ]);

        return $pdf->stream('Laporan-Perawatan-Individu-Row-' . $id . '-' . now()->format('YmdHis') . '.pdf');
    }
}
