<?php

namespace App\Services;

use App\Models\Farm;
use App\Models\User;
use App\Enums\RoleEnum;
use App\Models\FarmUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FarmService
{

    public function getFarmList()
    {
        $user = auth()->user();

        // Ambil data farm milik user dan relasi farm-nya
        $farmUsers = FarmUser::with('farm')
            ->where('user_id', $user->id)
            ->get()
            ->unique('farm_id') // hilangkan duplikat berdasarkan farm_id
            ->values(); // reset indeks agar rapih

        return $farmUsers;

    }

    public function findUser($username)
    {
        $user = User::verified()->where(function($query) use ($username) {
            $query->where('email', $username)
                ->orWhere('phone_number', $username);
        })->firstOrFail();

        return $user;
    }

    public function getUsers($farmId)
    {
        $farm = Farm::findOrFail($farmId);

        if(!$farm){
            return [
                'error' => true,
                'message' => "Farm not found",
                'http_code' => 404,
                'data' => null
            ];
        }

        if($farm->owner_id !== auth()->id()){
            return [
                'error' => true,
                'message' => "You don't have permission to access this",
                'http_code' => 403,
                'data' => null
            ];
        }

        $famUsers = FarmUser::with(['user' , 'farm'])->where('farm_id', $farmId)->get();

        return [
            'error' => false,
            'message' => "Success",
            'http_code' => 200,
            'data' => $famUsers
        ];
    }

    public function addUser($request, $farmId)
    {
        $data = null;
        $error = true;

        $user = User::find($request['user_id']);

        // Start transaction
        DB::beginTransaction();

        try {
            // First or create FarmUser
            $farmUser = FarmUser::firstOrCreate([
                'user_id' => $user->id,
                'farm_id' => $farmId,
                'farm_role' => $request['farm_role']
            ]);

            // Sync user roles without detaching existing roles
            $user->roles()->syncWithoutDetaching([
                RoleEnum::FARMER->value => [
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);

            // Commit transaction
            DB::commit();

            $data = $farmUser;
            $error = false;


        } catch (\Exception $e) {

            DB::rollBack();

        }

        return [
            'error' => $error,
            'data' => $data,
        ];
    }
}
