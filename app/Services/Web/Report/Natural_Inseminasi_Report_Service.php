<?php

namespace App\Services\Web\Report;

use App\Models\InseminationNatural;
use App\Models\LivestockBreed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PDF;

class Natural_Inseminasi_Report_Service
{
    /**
     * FORM pilih ternak + tanggal
     */
    public function index($farmId)
    {
        try {
            $farm = request()->attributes->get('farm');

            // Semua ternak betina untuk dropdown
            $livestocks = $farm->livestocks()
                ->whereHas('livestockSex', fn($q) => $q->whereRaw('LOWER(name) = ?', ['betina']))
                ->orderBy('id', 'DESC')
                ->get();

            // 🔥 tambahan: semua breed pejantan untuk dropdown filter
            $breeds = LivestockBreed::orderBy('name')->get();

            return view('pdf.care_livestock.natural_inseminasi.index', [
                'farm'       => $farm,
                'livestocks' => $livestocks,
                'breeds'     => $breeds, // 🔥 FIX: ini yang kurang
            ]);

        } catch (\Throwable $e) {
            Log::error('Natural Inseminasi Report Index Error', [
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
            'livestock_id' => 'required|exists:livestocks,id',
            'start_date'   => 'required|date',
            'end_date'     => 'required|date',
        ]);

        try {
            $farm = request()->attributes->get('farm');

            $items = InseminationNatural::with([
                    'insemination',
                    'reproductionCycle.livestock.livestockBreed',
                    'reproductionCycle.livestock.livestockType',
                ])
                ->whereHas('insemination', function ($q) use ($farm, $request) {
                    $q->where('farm_id', $farm->id)
                      ->whereRaw('LOWER(type) = ?', ['natural'])
                      ->whereBetween('transaction_date', [
                          $request->start_date,
                          $request->end_date
                      ]);
                })
                ->whereHas('reproductionCycle.livestock', function ($q) use ($request) {
                    $q->where('id', $request->livestock_id);
                })
                ->orderBy('id', 'DESC')
                ->get();

            return view('pdf.care_livestock.natural_inseminasi.detail', [
                'farm'    => $farm,
                'items'   => $items,
                'filters' => $request->only(['livestock_id','start_date','end_date']),
            ]);

        } catch (\Throwable $e) {
            Log::error('Natural Inseminasi Report Detail Error', [
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
            'livestock_id' => 'required|exists:livestocks,id',
            'start_date'   => 'required|date',
            'end_date'     => 'required|date',
        ]);

        try {
            $farm = request()->attributes->get('farm');

            $items = InseminationNatural::with([
                    'insemination',
                    'reproductionCycle.livestock.livestockBreed',
                    'reproductionCycle.livestock.livestockType',
                ])
                ->whereHas('insemination', function ($q) use ($farm, $request) {
                    $q->where('farm_id', $farm->id)
                      ->whereRaw('LOWER(type) = ?', ['natural'])
                      ->whereBetween('transaction_date', [
                          $request->start_date,
                          $request->end_date
                      ]);
                })
                ->whereHas('reproductionCycle.livestock', function ($q) use ($request) {
                    $q->where('id', $request->livestock_id);
                })
                ->orderBy('id', 'DESC')
                ->get();

            $filename = 'natural_inseminasi_report_' . date('YmdHis') . '.pdf';

            $pdf = PDF::loadView('pdf.care_livestock.natural_inseminasi.export', [
                'farm'    => $farm,
                'items'   => $items,
                'filters' => $request->only(['livestock_id','start_date','end_date']),
            ]);

            return $pdf->download($filename);

        } catch (\Throwable $e) {
            Log::error('Natural Inseminasi Report PDF Export Error', [
                'farm_id' => $farmId,
                'error'   => $e->getMessage(),
                'line'    => $e->getLine(),
                'file'    => $e->getFile(),
            ]);

            return back()->with('error', $e->getMessage());
        }
    }
}
