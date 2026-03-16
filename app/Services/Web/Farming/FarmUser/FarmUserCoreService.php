<?php

namespace App\Services\Web\Farming\FarmUser;

use App\Models\User;
use App\Models\FarmUser;
use App\Enums\RoleEnum;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FarmUserCoreService
{
    /**
     * Get all users for a farm
     */
    public function list($farmId, $filters = [])
    {
        $query = FarmUser::with(['user.profile', 'farm'])
            ->where('farm_id', $farmId);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['farm_role'])) {
            $query->where('farm_role', $filters['farm_role']);
        }

        return $query->latest()->paginate($filters['per_page'] ?? 10);
    }

    /**
     * Get a single farm user
     */
    public function get($farmId, $id)
    {
        return FarmUser::with(['user.profile', 'farm'])
            ->where('farm_id', $farmId)
            ->findOrFail($id);
    }

    /**
     * Add a user to a farm
     */
    public function store($farmId, array $data)
    {
        DB::beginTransaction();

        try {
            $user = User::find($data['user_id']);

            if (!$user) {
                throw new \Exception('User tidak ditemukan.');
            }

            // Check if user already exists in this farm
            $exists = FarmUser::where('user_id', $user->id)
                ->where('farm_id', $farmId)
                ->where('farm_role', $data['farm_role'])
                ->exists();

            if ($exists) {
                throw new \Exception('User sudah terdaftar dengan role ini di farm.');
            }

            $farmUser = FarmUser::create([
                'user_id' => $user->id,
                'farm_id' => $farmId,
                'farm_role' => $data['farm_role'],
            ]);

            // Assign FARMER role if not already
            if (!$user->roles()->where('role_id', RoleEnum::FARMER->value)->exists()) {
                $user->roles()->attach(RoleEnum::FARMER->value);
            }

            DB::commit();
            return $farmUser;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error adding farm user: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update farm user role
     */
    public function update($farmId, $id, array $data)
    {
        $farmUser = FarmUser::where('farm_id', $farmId)->findOrFail($id);
        $farmUser->update(['farm_role' => $data['farm_role']]);
        return $farmUser;
    }

    /**
     * Remove a user from a farm
     */
    public function destroy($farmId, $id)
    {
        DB::beginTransaction();

        try {
            $farmUser = FarmUser::where('farm_id', $farmId)
                ->whereIn('farm_role', ['ABK', 'ADMIN', 'DRIVER', 'MARKETING'])
                ->findOrFail($id);

            $userId = $farmUser->user_id;
            $farmUser->delete();

            // Detach FARMER role if user has no more farms
            if (!FarmUser::where('user_id', $userId)->exists()) {
                $user = User::find($userId);
                if ($user) {
                    $user->roles()->detach(RoleEnum::FARMER->value);
                }
            }

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error removing farm user: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Find user by email or phone
     */
    public function findUser($search)
    {
        return User::where('email', $search)
            ->orWhere('phone_number', $search)
            ->first();
    }
}
