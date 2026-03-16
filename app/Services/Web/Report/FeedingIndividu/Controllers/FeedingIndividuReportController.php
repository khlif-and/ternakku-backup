<?php

namespace App\Services\Web\Report\FeedingIndividu\Controllers;

use App\Models\Farm;
use App\Models\FeedingIndividuD;
use App\Services\Web\Report\FeedingIndividu\Services\FeedingIndividuReportService;
use App\Services\Web\Report\FeedingIndividu\Resources\FeedingIndividuReportResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PDF;
use Illuminate\Support\Facades\Log;

class FeedingIndividuReportController
{
    protected $service;

    public function __construct(FeedingIndividuReportService $service)
    {
        $this->service = $service;
    }

    public function index(Farm $farm, array $filters)
    {
        $details = $this->service->getReportData($farm, $filters);

        $details->getCollection()->transform(function ($item) {
            return (new FeedingIndividuReportResource($item))->resolve();
        });

        $summary = $this->service->getSummary($farm, $filters);

        return [
            'details' => $details,
            'summary' => $summary,
        ];
    }

    public function exportPdf(Request $request, $farmId)
    {
        try {
            $farm = Farm::findOrFail($farmId);
            $filters = $request->only(['start_date', 'end_date', 'pen_id', 'livestock_id']);

            $filters['start_date'] = $filters['start_date'] ?? now()->format('Y-m-d');
            $filters['end_date'] = $filters['end_date'] ?? now()->format('Y-m-d');

            $items = $this->service->getAll($farm, $filters);

            $penName = null;
            $groupedItems = null;

            if (!empty($filters['livestock_id'])) {
                $livestock = $farm->livestocks()->find($filters['livestock_id']);
                $penName = $livestock ? ($livestock->name . ' (' . ($livestock->eartag ?? '-') . ')') : null; // Misused variable name for display logic simplicity
            } elseif (!empty($filters['pen_id'])) {
                $pen = $farm->pens()->find($filters['pen_id']);
                $penName = $pen ? $pen->name : null;
            } else {
                $groupedItems = $items->groupBy(function ($item) {
                    return $item->livestock->pen->name ?? 'Tanpa Kandang';
                });
            }

            $pdf = PDF::loadView('pdf.care_livestock.feeding_individu.export', [
                'farm' => $farm,
                'items' => $items,
                'groupedItems' => $groupedItems,
                'filters' => $filters,
                'pen_name' => $penName,
                'mode' => !empty($filters['pen_id']) || !empty($filters['livestock_id']) ? 'filtered' : 'all'
            ]);

            $filename = 'laporan_pakan_individu_' . date('YmdHis') . '.pdf';

            return $pdf->download($filename);

        } catch (\Throwable $e) {
            Log::error('Feeding Individu Report PDF Export Error', [
                'farm_id' => $farmId,
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return back()->with('error', 'Gagal mengunduh PDF laporan pakan individu.');
        }
    }

    public function exportRowPdf($farmId, $id)
    {
        try {
            $farm = Farm::findOrFail($farmId);
            $item = FeedingIndividuD::with(['feedingH', 'livestock.pen', 'feedingIndividuItems'])
                ->whereHas('feedingH', function ($q) use ($farm) {
                    $q->where('farm_id', $farm->id)->where('type', 'individu');
                })
                ->findOrFail($id);

            $filters = [
                'start_date' => $item->feedingH->transaction_date,
                'end_date' => $item->feedingH->transaction_date,
            ];

            $items = collect([$item]);

            $pdf = PDF::loadView('pdf.care_livestock.feeding_individu.export', [
                'farm' => $farm,
                'items' => $items,
                'filters' => $filters,
                'pen_name' => $item->livestock->pen->name ?? '-',
                'mode' => 'row_single'
            ]);

            $filename = 'laporan_pakan_individu_' . Carbon::parse($item->feedingH->transaction_date)->format('Ymd') . '_' . $id . '.pdf';

            return $pdf->download($filename);

        } catch (\Throwable $e) {
            Log::error('Feeding Individu Report Single PDF Export Error', [
                'farm_id' => $farmId,
                'id' => $id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Gagal mengunduh PDF laporan pakan individu.');
        }
    }
}
