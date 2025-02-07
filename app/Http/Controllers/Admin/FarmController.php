<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\FarmService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Farming\FarmUserStoreRequest;

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

    public function findUser()
    {
        $username = request('username');

        $user = $this->farmService->findUser($username);

        return response()->json($user);
    }

    public function userList()
    {
        //TODO : Get farm id from session
        $farmId = session('selected_farm');

        $response = $this->farmService->getUsers($farmId);

        if ($response['error']) {
            return redirect()->back()->withErrors([
                'login_error' => $response['message']
            ]);
        }

        $users = $response['data'];

        return view('admin.farm.user_list' , compact('users'));
    }

    public function userCreate()
    {
        return view('admin.farm.user_create');
    }

    public function addUser(FarmUserStoreRequest $request)
    {
        $validated = $request->validated();
        $farmId = session('selected_farm');

        $response = $this->farmService->addUser($validated, $farmId);

        if ($response['error']) {
            return redirect()->back()->with('error', 'An error occurred while adding the user');
        }

        return redirect('qurban/farm/user-list')->with('success', 'User added to the farm successfully');
    }
}
