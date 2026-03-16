<?php

namespace App\Services\Web\Report\FeedingColony\Controllers;

use App\Services\Web\Report\FeedingColony\Services\FeedingColonyReportService;
use App\Services\Web\Report\FeedingColony\Resources\FeedingColonyReportResource;
use App\Helpers\ResponseHelper;
use App\Models\Farm;
use PDF;

class FeedingColonyReportController
{
    protected $service;

    public function __construct(FeedingColonyReportService $service)
    {
        $this->service = $service;
    }

    public function index(Farm $farm, array $filters)
    {
        $details = $this->service->getReportData($farm, $filters);

        // Transform the paginated items using the Resource, but keep the Paginator structure
        $details->getCollection()->transform(function ($item) {
            return (new FeedingColonyReportResource($item))->resolve();
        });

        $summary = $this->service->getSummary($farm, $filters);

        return [
            'details' => $details,
            'summary' => $summary,
        ];
    }

    public function exportPdf(\Illuminate\Http\Request $request, $farmId)
    {
        try {
            $farm = Farm::findOrFail($farmId);
            $filters = $request->only(['start_date', 'end_date', 'pen_id']);

            // Ensure filters are not null
            $filters['start_date'] = $filters['start_date'] ?? now()->format('Y-m-d');
            $filters['end_date'] = $filters['end_date'] ?? now()->format('Y-m-d');

            $items = $this->service->getAll($farm, $filters);

            $penName = null;
            $groupedItems = null;

            if (!empty($filters['pen_id'])) {
                $pen = $farm->pens()->find($filters['pen_id']);
                $penName = $pen ? $pen->name : null;
            } else {
                // If no specific pen is selected, group by pen for better readability in "All" mode
                $groupedItems = $items->groupBy(function ($item) {
                    return $item->pen->name ?? 'Tanpa Kandang';
                });
            }

            $pdf = \PDF::loadView('pdf.care_livestock.feeding_colony.export', [
                'farm' => $farm,
                'items' => $items,                          // Flat list (compatible with existing view)
                'groupedItems' => $groupedItems,            // Grouped list (for "All" mode)
                'filters' => $filters,
                'pen_name' => $penName,
                'mode' => !empty($filters['pen_id']) ? 'per_pen' : 'all'
            ]);

            $filename = 'laporan_pakan_koloni_' . date('YmdHis') . '.pdf';

            return $pdf->download($filename);

        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Feeding Colony Report PDF Export Error', [
                'farm_id' => $farmId,
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return back()->with('error', 'Gagal mengunduh PDF laporan pakan koloni.');
        }
    }

    public function exportRowPdf($farmId, $id)
    {
        try {
            $farm = Farm::findOrFail($farmId);
            // Fetch single record using service or model directly if service doesn't have find
            // Using query from service to consistency but filtering by ID
            $item = \App\Models\FeedingColonyD::with(['feedingH', 'pen', 'feedingColonyItems'])
                ->whereHas('feedingH', function ($q) use ($farm) {
                    $q->where('farm_id', $farm->id)->where('type', 'colony');
                })
                ->findOrFail($id);

            // Format filters for view
            $filters = [
                'start_date' => $item->feedingH->transaction_date,
                'end_date' => $item->feedingH->transaction_date,
            ];

            // Pass as collection to reuse view
            $items = collect([$item]);

            $pdf = \PDF::loadView('pdf.care_livestock.feeding_colony.export', [
                'farm' => $farm,
                'items' => $items,
                'filters' => $filters,
                'pen_name' => $item->pen->name,
            ]);

            $filename = 'laporan_pakan_koloni_' . \Carbon\Carbon::parse($item->feedingH->transaction_date)->format('Ymd') . '_' . $id . '.pdf';

            return $pdf->download($filename);

        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Feeding Colony Report Single PDF Export Error', [
                'farm_id' => $farmId,
                'id' => $id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Gagal mengunduh PDF laporan pakan koloni.');
        }
    }
}
