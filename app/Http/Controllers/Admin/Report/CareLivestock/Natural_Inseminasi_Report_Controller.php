<?php

namespace App\Http\Controllers\Admin\Report\CareLivestock;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Web\Report\Natural_Inseminasi_Report_Service;

class Natural_Inseminasi_Report_Controller extends Controller
{
    protected Natural_Inseminasi_Report_Service $service;

    public function __construct(Natural_Inseminasi_Report_Service $service)
    {
        $this->service = $service;
    }

    /**
     * FORM pilih ternak + tanggal (INDEX)
     */
    public function index($farmId)
    {
        return $this->service->index($farmId);
    }

    /**
     * Preview laporan (DETAIL)
     */
    public function detail(Request $request, $farmId)
    {
        return $this->service->detail($request, $farmId);
    }

    /**
     * Export PDF
     */
    public function exportPdf(Request $request, $farmId)
    {
        return $this->service->exportPdf($request, $farmId);
    }
}
