<?php

namespace App\Services\Web\Report\MutationIndividu\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Farm;
use App\Services\Web\Report\MutationIndividu\Services\MutationIndividuReportService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class MutationIndividuReportController extends Controller
{
    protected $service;

    public function __construct(MutationIndividuReportService $service)
    {
        $this->service = $service;
    }

    public function exportPdf(Request $request, $farmId)
    {
        $farm = Farm::findOrFail($farmId);
        $filters = $request->except(['page']);

        $data = $this->service->generateReport($farm->id, $filters);

        $pdf = Pdf::loadView('pdf.care_livestock.mutation_individu.export', [
            'data' => $data,
            'farm' => $farm,
            'filters' => $filters
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('laporan-mutasi-individu-' . now()->format('Y-m-d') . '.pdf');
    }

    public function exportRowPdf($farmId, $id)
    {
        $farm = Farm::findOrFail($farmId);
        $data = $this->service->getById($farmId, $id);

        if (!$data) {
            abort(404);
        }

        $pdf = Pdf::loadView('pdf.care_livestock.mutation_individu.export_row', [
            'data' => $data,
            'farm' => $farm,
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('laporan-mutasi-individu-detail-' . $data->transaction_number . '.pdf');
    }
}
