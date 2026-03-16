<?php

namespace App\Http\Controllers\Admin\Qurban;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    public function index()
    {
        $contracts = []; 
        return view('admin.qurban.contract.index', compact('contracts'));
    }

    public function create()
    {
        return view('admin.qurban.contract.create');
    }

    public function savingRegistrationIndex()
    {
        $savingRegistrations = []; 
        return view('admin.qurban.contract.saving_registration.index', compact('savingRegistrations'));
    }

    public function savingRegistrationCreate()
    {
        return view('admin.qurban.contract.saving_registration.create');
    }
}
