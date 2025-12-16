<?php

namespace App\Http\Controllers\Admin\Report\CareLivestock;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Web\Report\Pen_Report_Service;

class Pen_Report_Controller extends Controller
{
    protected Pen_Report_Service $service;

    public function __construct(Pen_Report_Service $service)
    {
        $this->service = $service;
    }

    // FORM laporan (pilih kandang & tanggal)
    public function index($farmId)
    {
        return $this->service->index($farmId);
    }

    // Tampilkan detail laporan
    public function detail(Request $request, $farmId)
    {
        return $this->service->detail($request, $farmId);
    }

    // Download PDF
    public function exportPdf(Request $request, $farmId)
    {
        return $this->service->exportPdf($request, $farmId);
    }
}
