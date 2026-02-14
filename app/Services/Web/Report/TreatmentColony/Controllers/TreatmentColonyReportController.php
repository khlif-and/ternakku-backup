<?php

namespace App\Services\Web\Report\TreatmentColony\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Web\Report\TreatmentColony\Services\TreatmentColonyReportService;
use App\Services\Web\Report\TreatmentColony\Resources\TreatmentColonyReportResource;
use App\Models\Farm;
use Illuminate\Http\Request;
use PDF; // Assuming PDF facade is available via aliases or needs explicit import

class TreatmentColonyReportController extends Controller
{
    protected $service;

    public function __construct(TreatmentColonyReportService $service)
    {
        $this->service = $service;
    }

    public function index(Farm $farm, array $filters = [])
    {
        $data = $this->service->getReportData($farm->id, $filters);
        $summary = $this->service->getSummary($farm->id, $filters);

        return [
            'details' => TreatmentColonyReportResource::collection($data),
            'summary' => $summary,
        ];
    }

    public function exportPdf(Request $request, $farm_id)
    {
        $farm = Farm::findOrFail($farm_id);
        $filters = $request->only(['start_date', 'end_date', 'pen_id']);

        // Pass $filters to getAll so exports respect current filters
        $data = $this->service->getAll($farm->id, $filters);
        $summary = $this->service->getSummary($farm->id, $filters);

        $pdf = \PDF::loadView('pdf.care_livestock.treatment_colony.export', [
            'data' => TreatmentColonyReportResource::collection($data)->resolve(),
            'farm' => $farm,
            'summary' => $summary,
            'filters' => $filters,
            'start_date' => $request->start_date ?? now()->format('Y-m-d'),
            'end_date' => $request->end_date ?? now()->format('Y-m-d'),
            'type' => 'all'
        ]);

        return $pdf->stream('Laporan-Perawatan-Koloni-' . now()->format('YmdHis') . '.pdf');
    }

    public function exportRowPdf(Request $request, $farm_id, $id)
    {
        $farm = Farm::findOrFail($farm_id);
        $item = $this->service->find($farm->id, $id);

        // Convert the single item to resource for consistency
        $data = [new TreatmentColonyReportResource($item)];

        $pdf = \PDF::loadView('pdf.care_livestock.treatment_colony.export', [
            'data' => collect($data)->map(fn($r) => $r->resolve())->first(), // Single item array
            'farm' => $farm,
            'type' => 'single'
        ]);

        return $pdf->stream('Laporan-Perawatan-Koloni-Row-' . $id . '-' . now()->format('YmdHis') . '.pdf');
    }
}
