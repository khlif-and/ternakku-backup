<?php

namespace App\Http\Controllers\Admin\Qurban;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PopulationReportController extends Controller
{
    public function index()
    {
        $populations = [];

        return view('admin.qurban.report.PopulationReport.index', compact('populations'));
    }

    public function create()
    {
        return view('admin.qurban.report.PopulationReport.create');
    }
}
