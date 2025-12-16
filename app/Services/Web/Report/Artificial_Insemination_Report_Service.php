<?php

namespace App\Services\Web\Report;

use App\Models\InseminationArtificial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PDF;

class Artificial_Insemination_Report_Service
{
    /**
     * FORM pilih pen + tanggal
     */
    public function index($farmId)
    {
        try {
            $farm = request()->attributes->get('farm');
            $pens = $farm->pens()->orderBy('name')->get();

            return view('pdf.care_livestock.artificial_inseminasi.index', [
                'farm' => $farm,
                'pens' => $pens,
            ]);

        } catch (\Throwable $e) {

            Log::error('Artificial Inseminasi Report Index Error', [
                'farm_id' => $farmId,
                'error'   => $e->getMessage(),
                'line'    => $e->getLine(),
                'file'    => $e->getFile(),
            ]);

            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * PREVIEW LAPORAN
     */
    public function detail(Request $request, $farmId)
    {
        $request->validate([
            'pen_id'     => 'required|exists:pens,id',
            'start_date' => 'required|date',
            'end_date'   => 'required|date',
        ]);

        try {
            $farm = request()->attributes->get('farm');

            $items = InseminationArtificial::with([
                    'insemination',
                    'reproductionCycle.livestock.livestockType',
                    'reproductionCycle.livestock.livestockBreed',
                    'reproductionCycle.livestock.pen',
                ])
                ->whereHas('insemination', function ($q) use ($farm, $request) {
                    $q->where('farm_id', $farm->id)
                      ->where('type', 'artificial')
                      ->whereBetween('date', [
                          $request->start_date,
                          $request->end_date
                      ]);
                })
                ->whereHas('reproductionCycle.livestock', function ($q) use ($request) {
                    $q->where('pen_id', $request->pen_id);
                })
                ->orderBy('id', 'DESC')
                ->get();

            return view('pdf.care_livestock.artificial_inseminasi.detail', [
                'farm'    => $farm,
                'items'   => $items,
                'filters' => $request->only(['pen_id','start_date','end_date']),
            ]);

        } catch (\Throwable $e) {

            Log::error('Artificial Inseminasi Report Detail Error', [
                'farm_id' => $farmId,
                'error'   => $e->getMessage(),
                'line'    => $e->getLine(),
                'file'    => $e->getFile(),
            ]);

            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * EXPORT PDF
     */
    public function exportPdf(Request $request, $farmId)
    {
        $request->validate([
            'pen_id'     => 'required|exists:pens,id',
            'start_date' => 'required|date',
            'end_date'   => 'required|date',
        ]);

        try {
            $farm = request()->attributes->get('farm');

            $items = InseminationArtificial::with([
                    'insemination',
                    'reproductionCycle.livestock.livestockType',
                    'reproductionCycle.livestock.livestockBreed',
                    'reproductionCycle.livestock.pen',
                ])
                ->whereHas('insemination', function ($q) use ($farm, $request) {
                    $q->where('farm_id', $farm->id)
                      ->where('type', 'artificial')
                      ->whereBetween('date', [
                          $request->start_date,
                          $request->end_date
                      ]);
                })
                ->whereHas('reproductionCycle.livestock', function ($q) use ($request) {
                    $q->where('pen_id', $request->pen_id);
                })
                ->orderBy('id', 'DESC')
                ->get();

            $filename = 'artificial_inseminasi_report_' . date('YmdHis') . '.pdf';

            $pdf = PDF::loadView('pdf.care_livestock.artificial_inseminasi.export', [
                'farm'    => $farm,
                'items'   => $items,
                'filters' => $request->only(['pen_id','start_date','end_date']),
            ]);

            return $pdf->download($filename);

        } catch (\Throwable $e) {

            Log::error('Artificial Inseminasi Report PDF Export Error', [
                'farm_id' => $farmId,
                'error'   => $e->getMessage(),
                'line'    => $e->getLine(),
                'file'    => $e->getFile(),
            ]);

            return back()->with('error', $e->getMessage());
        }
    }
}
