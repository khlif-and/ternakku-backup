<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\FarmService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Farming\FarmUserStoreRequest;
use App\Models\Farm;
use App\Models\FarmUser;

class FarmController extends Controller
{
    private $farmService;

    public function __construct(FarmService $farmService)
    {
        $this->farmService = $farmService;
    }

    public function create()
    {
        return view('admin.farm.create_farm');
    }

    public function selectFarm(Request $request)
    {
        if (session()->has('selected_farm') && $request->has('redirect_url')) {
            return redirect($request->get('redirect_url'));
        }

        $farms = $this->farmService->getFarmList();

        if ($farms->isEmpty()) {
            return view('admin.farm.create_farm');
        }

        return view('admin.farm.select_farm', compact('farms'));
    }

    public function selectFarmStore(Request $request)
    {
        session(['selected_farm' => $request->farm_id]);

        if ($request->filled('redirect_url')) {
            return redirect($request->redirect_url);
        }

        return redirect('/dashboard');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'farm_name' => 'required|string|max:255',
            'registration_date' => 'required|date',
            'qurban_partner' => 'nullable|boolean',
        ]);

        $data = [
            'name' => $validated['farm_name'],
            'registration_date' => $validated['registration_date'],
            'qurban_partner' => $request->has('qurban_partner'),
            'owner_id' => auth()->id(),
        ];

        $farm = $this->farmService->createFarm($data);


        session(['selected_farm' => $farm->id]);

        return redirect('select-farm')->with('success', 'Farm berhasil dibuat!');
    }

    public function findUser()
    {
        $username = request('username');
        $user = $this->farmService->findUser($username);
        return response()->json($user);
    }

    public function userList()
    {
        $farmId = session('selected_farm');

        $response = $this->farmService->getUsers($farmId);

        if ($response['error']) {
            return redirect()->back()->withErrors([
                'login_error' => $response['message']
            ]);
        }

        $users = $response['data'];

        return view('admin.farm.user_list', compact('users'));
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
