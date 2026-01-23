<?php

namespace App\Http\Controllers\Admin\Report\CareLivestock;

use App\Http\Controllers\Controller;
use App\Services\Web\Report\Pen_Report_Service;

class Pen_Report_Controller extends Controller
{
    protected Pen_Report_Service $service;

    public function __construct(Pen_Report_Service $service)
    {
        $this->service = $service;
    }

    /**
     * Show the Livewire-based pen report page
     */
    public function index($farmId)
    {
        return $this->service->index($farmId);
    }
}
