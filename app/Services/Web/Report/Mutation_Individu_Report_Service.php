<?php

namespace App\Services\Web\Report;

use App\Models\{MutationH, MutationIndividuD};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PDF;

class Mutation_Individu_Report_Service
{
    /**
     * FORM pilih pen + tanggal
     */
    public function index($farmId)
    {
        try {
            $farm = request()->attributes->get('farm');
            $pens = $farm->pens()->orderBy('name')->get();

            return view('pdf.care_livestock.mutation_individu.index', [
                'farm' => $farm,
                'pens' => $pens,
            ]);
        } catch (\Throwable $e) {

            Log::error('Mutation Individu Report Index Error', [
                'farm_id' => $farmId,
                'error' => $e->getMessage(),
                'line'  => $e->getLine(),
                'file'  => $e->getFile(),
            ]);

            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Tampilkan laporan detail
     */
    public function detail(Request $request, $farmId)
    {
        $request->validate([
            'pen_id'      => 'required|exists:pens,id',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date',
        ]);

        try {
            $farm = request()->attributes->get('farm');

            $items = MutationIndividuD::with(['mutationH', 'livestock'])
                ->whereHas('mutationH', function ($q) use ($farm, $request) {
                    $q->where('farm_id', $farm->id)
                        ->where('type', 'individu')
                        ->whereBetween('transaction_date', [
                            $request->start_date,
                            $request->end_date
                        ]);
                })
                ->where(function ($q) use ($request) {
                    if ($request->filled('pen_id')) {
                        $q->where('from', $request->pen_id)
                          ->orWhere('to', $request->pen_id);
                    }
                })
                ->orderBy('id', 'DESC')
                ->get();

            return view('pdf.care_livestock.mutation_individu.detail', [
                'farm' => $farm,
                'items' => $items,
                'filters' => $request->only(['pen_id','start_date','end_date']),
            ]);

        } catch (\Throwable $e) {

            Log::error('Mutation Individu Report Detail Error', [
                'farm_id' => $farmId,
                'error' => $e->getMessage(),
                'line'  => $e->getLine(),
                'file'  => $e->getFile(),
            ]);

            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Export PDF
     */
    public function exportPdf(Request $request, $farmId)
    {
        $request->validate([
            'pen_id'      => 'required|exists:pens,id',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date',
        ]);

        try {
            $farm = request()->attributes->get('farm');

            $items = MutationIndividuD::with(['mutationH', 'livestock'])
                ->whereHas('mutationH', function ($q) use ($farm, $request) {
                    $q->where('farm_id', $farm->id)
                        ->where('type', 'individu')
                        ->whereBetween('transaction_date', [
                            $request->start_date,
                            $request->end_date
                        ]);
                })
                ->where(function ($q) use ($request) {
                    if ($request->filled('pen_id')) {
                        $q->where('from', $request->pen_id)
                          ->orWhere('to', $request->pen_id);
                    }
                })
                ->orderBy('id', 'DESC')
                ->get();

            $filename = 'mutation_individu_report_' . date('YmdHis') . '.pdf';

            $pdf = PDF::loadView('pdf.care_livestock.mutation_individu.export', [
                'farm' => $farm,
                'items' => $items,
                'filters' => $request->only(['pen_id','start_date','end_date']),
            ]);

            return $pdf->download($filename);

        } catch (\Throwable $e) {

            Log::error('Mutation Individu Report PDF Export Error', [
                'farm_id' => $farmId,
                'error' => $e->getMessage(),
                'line'  => $e->getLine(),
                'file'  => $e->getFile(),
            ]);

            return back()->with('error', $e->getMessage());
        }
    }
}
