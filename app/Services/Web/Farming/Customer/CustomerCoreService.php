<?php

namespace App\Services\Web\Farming\Customer;

use App\Models\QurbanCustomer;
use App\Models\QurbanCustomerAddress;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CustomerCoreService
{
    public function listCustomers($farm)
    {
        return QurbanCustomer::where('farm_id', $farm->id)
            ->filterMarketing($farm->id)
            ->get();
    }

    public function storeCustomer($farm, array $data, $creatorId): QurbanCustomer
    {
        return DB::transaction(function () use ($farm, $data, $creatorId) {
            $user = \App\Models\User::create([
                'name'              => $data['name'],
                'email'             => $data['email'] ?? null,
                'phone_number'      => $data['phone'] ?? null,
                'password'          => \Illuminate\Support\Facades\Hash::make(Str::random(16)),
                'email_verified_at' => now(),
            ]);

            $user->roles()->attach(\App\Enums\RoleEnum::REGISTERED_USER->value, [
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return QurbanCustomer::create([
                'farm_id'    => $farm->id,
                'user_id'    => $user->id,
                'created_by' => $creatorId,
            ]);
        });
    }

    public function findCustomer(int $id, int $farmId): QurbanCustomer
    {
        return QurbanCustomer::where('farm_id', $farmId)->findOrFail($id);
    }

    public function updateCustomer(int $id, int $farmId, array $data): QurbanCustomer
    {
        $customer = $this->findCustomer($id, $farmId);

        return DB::transaction(function () use ($customer, $data) {
            if ($customer->user) {
                $customer->user->update([
                    'name'         => $data['name'],
                    'phone_number' => $data['phone'] ?? null,
                    'email'        => $data['email'] ?? null,
                ]);
            }

            return $customer;
        });
    }

    public function deleteCustomer(int $id, int $farmId): void
    {
        $customer = $this->findCustomer($id, $farmId);
        $customer->delete();
    }

    public function listAddresses($customerId, int $farmId)
    {
        return QurbanCustomerAddress::where('qurban_customer_id', $customerId)
            ->where('farm_id', $farmId)
            ->get();
    }

    public function storeAddress($farm, $customerId, array $data): QurbanCustomerAddress
    {
        $this->findCustomer($customerId, $farm->id);

        return QurbanCustomerAddress::create([
            'farm_id'            => $farm->id,
            'qurban_customer_id' => $customerId,
            'name'               => $data['name'],
            'description'        => $data['description'] ?? '',
            'region_id'          => $data['region_id'],
            'postal_code'        => $data['postal_code'] ?? '',
            'address_line'       => $data['address_line'],
            'longitude'          => $data['longitude'] ?? null,
            'latitude'           => $data['latitude'] ?? null,
        ]);
    }

    public function findAddress(int $id, int $farmId): QurbanCustomerAddress
    {
        return QurbanCustomerAddress::where('farm_id', $farmId)->findOrFail($id);
    }

    public function updateAddress(int $id, int $farmId, array $data): QurbanCustomerAddress
    {
        $address = $this->findAddress($id, $farmId);

        $address->update([
            'name'         => $data['name'],
            'description'  => $data['description'] ?? '',
            'region_id'    => $data['region_id'],
            'postal_code'  => $data['postal_code'] ?? '',
            'address_line' => $data['address_line'],
            'longitude'    => $data['longitude'] ?? null,
            'latitude'     => $data['latitude'] ?? null,
        ]);

        return $address;
    }

    public function deleteAddress(int $id, int $farmId): void
    {
        $address = $this->findAddress($id, $farmId);
        $address->delete();
    }
}