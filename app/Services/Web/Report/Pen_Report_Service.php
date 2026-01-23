<?php

namespace App\Services\Web\Report;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Farm;

class Pen_Report_Service
{
    /**
     * Show the Livewire-based pen report page
     */
    public function index($farmId)
    {
        try {
            $farm = request()->attributes->get('farm');

            return view('admin.care_livestock.report.pen_report', [
                'farm' => $farm,
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
}
