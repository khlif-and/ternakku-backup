<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\FarmService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Farming\FarmUserStoreRequest;
use App\Models\Farm;
use App\Models\FarmDetail;
use App\Models\FarmUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

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
    if (!$request->filled('farm_id')) {
        return back()->withErrors(['farm_id' => 'Silakan pilih peternakan terlebih dahulu.']);
    }

    session(['selected_farm' => $request->farm_id]);

    if ($request->filled('redirect_url')) {
        return redirect($request->redirect_url);
    }

    return redirect('/dashboard');
}


    public function store(Request $request)
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'description'       => 'nullable|string|max:1000',
            'region_id'         => 'required|numeric',
            'postal_code'       => 'nullable|string|max:10',
            'address_line'      => 'required|string|max:255',
            'longitude'         => 'nullable|numeric',
            'latitude'          => 'nullable|numeric',
            'capacity'          => 'nullable|integer|min:1',
            'logo'              => 'required|image|mimes:jpg,jpeg,png,gif',
            'cover_photo'       => 'required|image|mimes:jpg,jpeg,png,gif',
        ]);

        $regionId = $request->region_id;

        // Cek apakah region tersedia di database lokal
        if (!\App\Models\Region::where('id', $regionId)->exists()) {
            $regionData = null;
            $page = 1;
            $hasMore = true;
            $maxPage = 5; // Batas maksimal loop agar tidak infinite loop

            while (!$regionData && $hasMore && $page <= $maxPage) {
                try {
                    $response = Http::timeout(5)->retry(2, 100)->get('https://feedmill.ternakku.com/api/data-master/region', [
                        'name' => '',
                        'per_page' => 50, // Ubah jadi 50 agar tidak berat
                        'page' => $page,
                    ]);

                    if (!$response->successful()) {
                        Log::error('❌ Gagal ambil region dari API', [
                            'status' => $response->status(),
                            'body' => $response->body(),
                        ]);
                        return back()->withErrors(['region_id' => 'Gagal mengambil data wilayah dari server.']);
                    }

                    $dataPage = $response->json()['data']['data'] ?? [];
                    $regionData = collect($dataPage)->first(function ($item) use ($regionId) {
                        return (string) $item['id'] === (string) $regionId;
                    });

                    $hasMore = !empty($response->json()['data']['next_page_url']);
                    $page++;
                } catch (\Exception $e) {
                    Log::error('🔥 Error saat request region', [
                        'message' => $e->getMessage(),
                    ]);
                    return back()->withErrors(['region_id' => 'Terjadi kesalahan saat mengambil data wilayah.']);
                }
            }

            if (!$regionData) {
                return back()->withErrors(['region_id' => 'Wilayah tidak ditemukan di server.']);
            }

            \App\Models\Region::create([
                'id' => $regionData['id'],
                'name' => $regionData['name'],
                'province_id' => $regionData['province_id'],
                'province_name' => $regionData['province_name'],
                'regency_id' => $regionData['regency_id'],
                'regency_name' => $regionData['regency_name'],
                'district_id' => $regionData['district_id'],
                'district_name' => $regionData['district_name'],
                'village_id' => $regionData['village_id'],
                'village_name' => $regionData['village_name'],
            ]);
        }

        $data = [
            'name' => $request->name,
            'owner_id' => auth()->id(),
            'registration_date' => now(),
            'qurban_partner' => $request->has('qurban_partner'),
        ];

        $farm = $this->farmService->createFarm($data);

        \App\Models\FarmUser::create([
            'user_id' => auth()->id(),
            'farm_id' => $farm->id,
            'farm_role' => 'OWNER',
        ]);

        try {
            $logoPath = $request->hasFile('logo') ? $request->file('logo')->store('farms/logo', 'public') : null;
            $coverPath = $request->hasFile('cover_photo') ? $request->file('cover_photo')->store('farms/cover', 'public') : null;

            Log::info('🚜 FarmDetail Incoming Data', [
                'farm_id' => $farm->id,
                'region_id' => $request->region_id,
                'logoPath' => $logoPath,
                'coverPath' => $coverPath,
            ]);

            \App\Models\FarmDetail::create([
                'farm_id' => $farm->id,
                'description' => $request->description,
                'region_id' => $request->region_id,
                'postal_code' => $request->postal_code,
                'address_line' => $request->address_line,
                'longitude' => $request->longitude,
                'latitude' => $request->latitude,
                'capacity' => $request->capacity,
                'logo' => $logoPath,
                'cover_photo' => $coverPath,
            ]);
        } catch (\Throwable $e) {
            Log::error('❌ Gagal simpan FarmDetail', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return back()->with('error', 'Gagal menyimpan detail farm: ' . $e->getMessage());
        }

        session(['selected_farm' => $farm->id]);

        return redirect('/dashboard')->with('success', 'Farm berhasil dibuat!');


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
        $farm = \App\Models\Farm::find($farmId);

        $response = $this->farmService->getUsers($farmId);

        if ($response['error']) {
            return redirect()->back()->withErrors([
                'login_error' => $response['message']
            ]);
        }

        $users = $response['data'];
        return view('admin.farm.user_list', compact('users', 'farm'));
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
