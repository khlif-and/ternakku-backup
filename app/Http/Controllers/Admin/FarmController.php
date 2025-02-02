<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\FarmService;

class FarmController extends Controller
{
    private $farmService;

    public function __construct(FarmService $farmService)
    {
        $this->farmService = $farmService;
    }

    public function selectFarm()
    {
        $farms = $this->farmService->getFarmList();

        return view('admin.farm.select_farm', compact('farms'));
    }

    public function selectFarmStore(Request $request)
    {
        session(['selected_farm' => $request->farm_id]);

        return redirect($request->redirect_url);
    }

    public function userList()
    {
        //TODO : Get farm id from session
        $farmId = 1;

        $response = $this->farmService->getUsers($farmId);

        if ($response['error']) {
            return redirect()->back()->withErrors([
                'login_error' => $response['message']
            ]);
        }

        $users = $response['data'];

        return view('admin.farm.user_list' , compact('users'));
    }
}
