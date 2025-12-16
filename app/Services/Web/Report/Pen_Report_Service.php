<?php

namespace App\Services\Web\Report;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PDF;

class Pen_Report_Service
{
    /**
     * FORM pilih pen + tanggal
     */
    public function index($farmId)
    {
        try {
            $farm = request()->attributes->get('farm');
            $pens = $farm->pens()->orderBy('name')->get();

            return view('pdf.care_livestock.index', [
                'farm' => $farm,
                'pens' => $pens,
            ]);
        } catch (\Throwable $e) {
            Log::error('Pen Report Index Error', [
                'error' => $e->getMessage(),
                'line'  => $e->getLine(),
                'file'  => $e->getFile(),
            ]);

            return back()->with('error', 'Terjadi kesalahan saat membuka halaman laporan.');
        }
    }

    /**
     * Tampilkan laporan detail sebelum export PDF
     */
    public function detail(Request $request, $farmId)
    {
        $request->validate([
            'pen_id'    => 'required|exists:pens,id',
            'from_date' => 'required|date',
            'to_date'   => 'required|date|after_or_equal:from_date',
        ]);

        try {
            $farm = request()->attributes->get('farm');
            $pen  = $farm->pens()->findOrFail($request->pen_id);

            return view('pdf.care_livestock.index', [
                'farm'      => $farm,
                'pens'      => [$pen],
                'pen'       => $pen,
                'from_date' => $request->from_date,
                'to_date'   => $request->to_date,
            ]);
        } catch (\Throwable $e) {
            Log::error('Pen Report Detail Error', [
                'error' => $e->getMessage(),
                'line'  => $e->getLine(),
                'file'  => $e->getFile(),
            ]);

            return back()->with('error', 'Terjadi kesalahan saat memuat laporan.');
        }
    }

    /**
     * EXPORT PDF
     */
    public function exportPdf(Request $request, $farmId)
    {
        $request->validate([
            'pen_id'    => 'required|exists:pens,id',
            'from_date' => 'required|date',
            'to_date'   => 'required|date|after_or_equal:from_date',
        ]);

        try {
            $farm = request()->attributes->get('farm');
            $pen  = $farm->pens()->findOrFail($request->pen_id);

            $pdf = PDF::loadView('pdf.care_livestock.pen_report', [
                'farm'      => $farm,
                'pen'       => $pen,
                'from_date' => $request->from_date,
                'to_date'   => $request->to_date,
            ])->setPaper('A4', 'portrait');

            return $pdf->download(
                'Laporan_Kandang_' . $pen->name . '_' . now()->format('Ymd_His') . '.pdf'
            );
        } catch (\Throwable $e) {
            Log::error('Pen Report Export Error', [
                'error' => $e->getMessage(),
                'line'  => $e->getLine(),
                'file'  => $e->getFile(),
            ]);

            return back()->with('error', 'Gagal mengekspor PDF.');
        }
    }
}
