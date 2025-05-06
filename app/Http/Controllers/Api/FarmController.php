<?php

namespace App\Http\Controllers\Api;

use App\Models\Farm;
use App\Models\User;
use App\Enums\RoleEnum;
use App\Models\Profile;
use App\Models\FarmUser;
use App\Models\FarmDetail;
use App\Services\FarmService;
use App\Helpers\ResponseHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Resources\FarmListResource;
use App\Http\Resources\FarmDetailResource;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\Farming\FindUserRequest;
use App\Http\Requests\Farming\FarmStoreRequest;
use App\Http\Requests\Farming\FarmUpdateRequest;
use App\Http\Resources\Farming\FarmUserResource;
use App\Http\Requests\Farming\FarmUserStoreRequest;
use App\Http\Requests\Farming\FarmUserRemoveRequest;
use App\Http\Requests\Farming\FarmUpdateProfileUserRequest;

class FarmController extends Controller
{
    private $farmService;

    public function __construct(FarmService $farmService)
    {
        $this->farmService = $farmService;
    }

    public function index()
    {
        $farms = $this->farmService->getFarmList();

        $data = FarmDetailResource::collection($farms->pluck('farm'));

        // Tentukan pesan respons
        $message = $farms->count() > 0 ? 'Farms retrieved successfully' : 'Data empty';

        // Kembalikan respons dengan data dan pesan
        return ResponseHelper::success($data, $message);

    }

    public function detail($id)
    {
        // Cari farm berdasarkan user_id dan farm_id
        $farmUser = FarmUser::with('farm')->where('user_id', auth()->id())->where('farm_id', $id)->first();

        // Jika farm tidak ditemukan, kembalikan respons error
        if (!$farmUser || !$farmUser->farm) {
            return ResponseHelper::error('Farm not found', 404);
        }

        // Jika farm ditemukan, gunakan FarmDetailResource untuk mengambil detailnya
        $data = new FarmDetailResource($farmUser->farm);

        return ResponseHelper::success($data, 'Farm detail retrieved successfully');
    }

    public function store(FarmStoreRequest $request)
    {
        $validated = $request->validated();
        $user = auth()->user();
        $ownerId = auth()->id(); // Mendapatkan ID pengguna yang sedang login

        DB::beginTransaction();

        try {
            // Simpan data ke tabel farms
            $farm = Farm::create([
                'name'              => $validated['name'],
                'owner_id'          => $ownerId,
                'registration_date' => now()->toDateString(), // Mengatur registration_date menjadi tanggal saat ini tanpa waktu
            ]);

            // Siapkan data untuk FarmDetail
            $farmDetailData = [
                'farm_id'      => $farm->id,
                'description'  => $validated['description'],
                'region_id'    => $validated['region_id'],
                'postal_code'  => $validated['postal_code'] ?? '',
                'address_line' => $validated['address_line'],
                'longitude'    => $validated['longitude'],
                'latitude'     => $validated['latitude'],
                'capacity'     => $validated['capacity'],
            ];

            // Handle logo upload if present
            if (isset($validated['logo']) && $request->hasFile('logo')) {
                $file = $validated['logo'];
                $fileName = time() . '-logo-' . $file->getClientOriginalName();
                $filePath = 'farms/logos/';
                $farmDetailData['logo'] = uploadNeoObject($file, $fileName, $filePath);
            }

            // Handle cover photo upload if present
            if (isset($validated['cover_photo']) && $request->hasFile('cover_photo')) {
                $file = $validated['cover_photo'];
                $fileName = time() . '-cover-' . $file->getClientOriginalName();
                $filePath = 'farms/covers/';
                $farmDetailData['cover_photo'] = uploadNeoObject($file, $fileName, $filePath);
            }

            // Simpan FarmDetail dengan data yang telah di-assign
            FarmDetail::create($farmDetailData);

            FarmUser::create([
                'farm_id' => $farm->id,
                'user_id' => $ownerId
            ]);

            // Menambahkan peran FARMER jika belum ada
            $user->roles()->syncWithoutDetaching([
                RoleEnum::FARMER->value => [
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);

            // Commit transaksi
            DB::commit();

            return ResponseHelper::success(new FarmDetailResource($farm), 'Farm created successfully', 200);
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();

            return ResponseHelper::error('Failed to create farm: ' . $e->getMessage(), 500);
        }
    }

    public function update(FarmUpdateRequest $request, $id)
    {
        $validated = $request->validated();

        // Temukan farm berdasarkan ID
        $farm = Farm::where('owner_id' , auth()->id())->findOrFail($id);

        // Mulai transaksi DB
        DB::transaction(function () use ($validated, $farm, $request) {
            // Update data di tabel farms
            $farm->update([
                'name' => $validated['name'],
            ]);

            // Temukan data FarmDetail yang terkait
            $farmDetail = $farm->farmDetail;

            // Siapkan data untuk FarmDetail
            $farmDetailData = [
                'description'  => $validated['description'],
                'region_id'    => $validated['region_id'],
                'postal_code'  => $validated['postal_code'] ?? '',
                'address_line' => $validated['address_line'],
                'longitude'    => $validated['longitude'],
                'latitude'     => $validated['latitude'],
                'capacity'     => $validated['capacity'],
            ];

            // Handle logo upload if present
            if (isset($validated['logo']) && $request->hasFile('logo')) {
                // Hapus logo lama jika ada
                if ($farmDetail && $farmDetail->logo) {
                    deleteNeoObject($farmDetail->logo);
                }

                // Unggah logo baru
                $file = $validated['logo'];
                $fileName = time() . '-logo-' . $file->getClientOriginalName();
                $filePath = 'farms/logos/';
                $farmDetailData['logo'] = uploadNeoObject($file, $fileName, $filePath);
            }

            // Handle cover photo upload if present
            if (isset($validated['cover_photo']) && $request->hasFile('cover_photo')) {
                // Hapus cover photo lama jika ada
                if ($farmDetail && $farmDetail->cover_photo) {
                    deleteNeoObject($farmDetail->cover_photo);
                }

                // Unggah cover photo baru
                $file = $validated['cover_photo'];
                $fileName = time() . '-cover-' . $file->getClientOriginalName();
                $filePath = 'farms/covers/';
                $farmDetailData['cover_photo'] = uploadNeoObject($file, $fileName, $filePath);
            }

            // Update FarmDetail dengan data yang telah di-assign
            $farm->farmDetail()->update($farmDetailData);
        });

        return ResponseHelper::success(new FarmDetailResource($farm), 'Farm updated successfully');
    }

    public function destroy($id)
    {
        $farm = Farm::where('owner_id', auth()->id())->findOrFail($id);

        DB::beginTransaction();

        try {
            // Hapus logo dan cover photo jika ada
            if ($farm->farmDetail && $farm->farmDetail->logo) {
                deleteNeoObject($farm->farmDetail->logo);
            }
            if ($farm->farmDetail && $farm->farmDetail->cover_photo) {
                deleteNeoObject($farm->farmDetail->cover_photo);
            }

            // Hapus data di tabel FarmUser
            FarmUser::where('farm_id' , $farm->id)->delete();

            // Hapus data di tabel FarmDetail
            $farm->farmDetail()->delete();

            // Hapus data di tabel Farm
            $farm->delete();

            // Commit transaksi
            DB::commit();

            return ResponseHelper::success(null, 'Farm deleted successfully', 200);
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();

            return ResponseHelper::error('Failed to delete farm: ' . $e->getMessage(), 500);
        }
    }

    public function userList($farm_id)
    {
        $farm = Farm::findOrFail($farm_id);

        if ($farm->owner_id !== auth()->id()) {
            return ResponseHelper::error("You don't have permission to access this", 403);
        }

        $query = FarmUser::with(['user', 'farm'])
            ->where('farm_id', $farm_id);

        // Filter optional berdasarkan user_id
        if (request()->has('user_id')) {
            $query->where('user_id', request('user_id'));
        }

        // Filter optional berdasarkan farm_role
        if (request()->has('farm_role')) {
            $query->where('farm_role', request('farm_role'));
        }

        $farmUsers = $query->get();

        $data = FarmUserResource::collection($farmUsers);

        $message = $farmUsers->count() > 0 ? 'Data retrieved successfully' : 'Data empty';

        return ResponseHelper::success($data, $message);
    }

    public function findUser(FindUserRequest $request, $farm_id)
    {
        $validatedData = $request->validated();

        $user = $this->farmService->findUser($validatedData['username']);

        return ResponseHelper::success($user, 'Data retrieved successfully');
    }


    public function listUser($farm_id)
    {
        $farm = $request->attributes->get('farm');

        $farmUsers = FarmUser::where('farm_id' , $farm->id)->get();

        $data = FarmUserResource::collection($farmUsers);

        $message = $data->count() > 0 ? 'Users retrieved successfully' : 'Data empty';

        return ResponseHelper::success($data, $message);
    }



    public function addUser(FarmUserStoreRequest $request, $farm_id)
    {
        $farm = $request->attributes->get('farm');

        $validated = $request->validated();

        $response = $this->farmService->addUser($validated, $farm_id);

        if($response['error']){
            return ResponseHelper::error('An error occurred while adding the user', 500);
        }

        $data = new FarmUserResource($response['data']);

        return ResponseHelper::success($data, 'User added to the farm successfully');
    }

    public function removeUser(FarmUserRemoveRequest $request, $farm_id)
    {
        $farm = $request->attributes->get('farm');

        $validated = $request->validated();

        $user = User::find($validated['user_id']);

        if (!$user) {
            return ResponseHelper::error('User not found', 404);
        }

        // Start transaction
        DB::beginTransaction();

        try {
            // Find and delete FarmUser
            $farmUser = FarmUser::where('user_id', $user->id)
                ->where('farm_id', $farm->id)
                ->whereIn('farm_role' , ['ABK' , 'ADMIN'])
                ->first();

            if ($farmUser) {
                $farmUser->delete();
            } else {
                return ResponseHelper::error('User is owner or not part of this farm', 404);
            }

            if(!FarmUser::where('user_id', $user->id)->where('farm_id', $farm->id)->exists()){
                $user->roles()->detach(RoleEnum::FARMER->value);
            }

            // Commit transaction
            DB::commit();

            return ResponseHelper::success(null , 'User removed from the farm successfully');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            // Rollback transaction on failure
            DB::rollBack();

            return ResponseHelper::error('An error occurred while removing the user', 500);
        }
    }

    public function updateProfileUser(FarmUpdateProfileUserRequest $request, $farm_id)
    {
        $validated = $request->validated();

        DB::beginTransaction();

        try {

            $farmUser = FarmUser::where('user_id', $validated['user_id'])
                ->where('farm_id', $farm_id)
                ->firstOrFail();

            $user = User::findOrFail($validated['user_id']);

            $user->update([
                'name' => $validated['name'],
            ]);

            $profile = $user->profile;

            if (isset($validated['photo']) && $request->hasFile('photo')) {

                if ($profile && $profile->photo) {
                    deleteNeoObject($profile->photo);
                }

                $file = $validated['photo'];
                $fileName = time() . '-profile-' . $file->getClientOriginalName();
                $filePath = 'profile/';
                $profileData['photo'] = uploadNeoObject($file, $fileName, $filePath);

            }

            if($profile){
                $profile->update($profileData);
            }else{
                $profileData['user_id'] = $user->id;
                // dd($profileData);
                Profile::create($profileData);
            }

            DB::commit();

            return ResponseHelper::success(new UserResource($user), 'Profile updated successfully', 200);

        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();

            return ResponseHelper::error('Failed to update profile: ' . $e->getMessage(), 500);
        }
    }
}
