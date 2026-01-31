<?php

namespace App\Http\Controllers\Admin\Qurban;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    // ===== Kontrak Qurban =====
    public function index()
    {
        $contracts = []; // data dummy
        return view('admin.qurban.contract.index', compact('contracts'));
    }

    public function create()
    {
        return view('admin.qurban.contract.create');
    }

    // ===== Pendaftaran Tabungan =====
    public function savingRegistrationIndex()
    {
        $savingRegistrations = []; // data dummy
        return view('admin.qurban.contract.saving_registration.index', compact('savingRegistrations'));
    }

    public function savingRegistrationCreate()
    {
        return view('admin.qurban.contract.saving_registration.create');
    }
}
