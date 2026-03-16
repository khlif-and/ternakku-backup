<?php

namespace App\Services\Web\Shared\Driver;

use App\Models\User;
use App\Models\FarmUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
        try {
            $farmUser = DB::transaction(function () use ($farmId, $data) {
                $user = User::where('email', $data['email'])->first();

                if (!$user) {
                    $user = User::create([
                        'name' => $data['name'],
                        'email' => $data['email'],
                        'phone_number' => $data['phone_number'] ?? null,
                        'password' => Hash::make($data['password'] ?? Str::random(16)),
                        'email_verified_at' => now(),
                    ]);
                }

                $existingFarmUser = FarmUser::where('farm_id', $farmId)
                    ->where('user_id', $user->id)
                    ->first();

                if ($existingFarmUser) {
                    throw new \RuntimeException('User sudah terdaftar di farm ini.');
                }

                return FarmUser::create([
                    'farm_id' => $farmId,
                    'user_id' => $user->id,
                    'farm_role' => 'DRIVER',
                ]);
            });

            return ['error' => false, 'data' => $farmUser->load('user')];
        } catch (\Throwable $e) {
            Log::error('Failed to store driver: ' . $e->getMessage());
            return ['error' => true, 'message' => 'Gagal menambahkan pengemudi.'];
        }
    }

    public function update($farmId, $id, array $data)
    {
        try {
            $farmUser = DB::transaction(function () use ($farmId, $id, $data) {
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

                return $farmUser;
            });

            return ['error' => false, 'data' => $farmUser->load('user')];
        } catch (\Throwable $e) {
            Log::error('Failed to update driver: ' . $e->getMessage());
            return ['error' => true, 'message' => 'Gagal memperbarui pengemudi.'];
        }
    }

    public function destroy($farmId, $id)
    {
        try {
            DB::transaction(function () use ($farmId, $id) {
                $farmUser = FarmUser::where('farm_id', $farmId)
                    ->where('farm_role', 'DRIVER')
                    ->where('id', $id)
                    ->firstOrFail();

                $farmUser->delete();
            });

            return ['error' => false];
        } catch (\Throwable $e) {
            Log::error('Failed to delete driver: ' . $e->getMessage());
            return ['error' => true, 'message' => 'Gagal menghapus pengemudi.'];
        }
    }
}
