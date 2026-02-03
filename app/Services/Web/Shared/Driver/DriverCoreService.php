<?php

namespace App\Services\Web\Shared\Driver;

use App\Models\User;
use App\Models\FarmUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class DriverCoreService
{
    public function get($farmId, $id)
    {
        return FarmUser::where('farm_id', $farmId)
            ->where('farm_role', 'DRIVER')
            ->where('id', $id)
            ->with('user')
            ->firstOrFail();
    }

    public function store($farmId, array $data)
    {
        DB::beginTransaction();
        try {
            $user = User::where('email', $data['email'])->first();

            if (!$user) {
                $user = User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'phone_number' => $data['phone_number'] ?? null,
                    'password' => Hash::make($data['password'] ?? 'driver123'),
                    'email_verified_at' => now(),
                ]);
            }

            $existingFarmUser = FarmUser::where('farm_id', $farmId)
                ->where('user_id', $user->id)
                ->first();

            if ($existingFarmUser) {
                throw new \Exception('User sudah terdaftar di farm ini.');
            }

            $farmUser = FarmUser::create([
                'farm_id' => $farmId,
                'user_id' => $user->id,
                'farm_role' => 'DRIVER',
            ]);

            DB::commit();
            return ['error' => false, 'data' => $farmUser->load('user')];
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Failed to store driver: ' . $e->getMessage());
            return ['error' => true, 'message' => $e->getMessage()];
        }
    }

    public function update($farmId, $id, array $data)
    {
        DB::beginTransaction();
        try {
            $farmUser = FarmUser::where('farm_id', $farmId)
                ->where('farm_role', 'DRIVER')
                ->where('id', $id)
                ->firstOrFail();

            $user = $farmUser->user;
            $user->update([
                'name' => $data['name'],
                'phone_number' => $data['phone_number'] ?? $user->phone_number,
            ]);

            if (!empty($data['password'])) {
                $user->update(['password' => Hash::make($data['password'])]);
            }

            DB::commit();
            return ['error' => false, 'data' => $farmUser->load('user')];
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Failed to update driver: ' . $e->getMessage());
            return ['error' => true, 'message' => $e->getMessage()];
        }
    }

    public function destroy($farmId, $id)
    {
        DB::beginTransaction();
        try {
            $farmUser = FarmUser::where('farm_id', $farmId)
                ->where('farm_role', 'DRIVER')
                ->where('id', $id)
                ->firstOrFail();

            $farmUser->delete();

            DB::commit();
            return ['error' => false];
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Failed to delete driver: ' . $e->getMessage());
            return ['error' => true, 'message' => $e->getMessage()];
        }
    }
}
