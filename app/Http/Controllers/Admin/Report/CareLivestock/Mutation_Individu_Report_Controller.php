<?php

namespace App\Http\Controllers\Admin\Report\CareLivestock;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// FIX DI SINI
use App\Services\Web\Report\Mutation_Individu_Report_Service;

class Mutation_Individu_Report_Controller extends Controller
{
    // FIX TYPE 1
    protected Mutation_Individu_Report_Service $service;

    // FIX TYPE 2
    public function __construct(Mutation_Individu_Report_Service $service)
    {
        $this->service = $service;
    }

    public function index($farmId)
    {
        return $this->service->index($farmId);
    }

    public function detail(Request $request, $farmId)
    {
        return $this->service->detail($request, $farmId);
    }

    public function exportPdf(Request $request, $farmId)
    {
        return $this->service->exportPdf($request, $farmId);
    }
}
