<?php

namespace App\Services\Web\Farming\Customer;

use App\Models\QurbanCustomer;
use App\Models\QurbanCustomerAddress;
use Illuminate\Support\Facades\DB;

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
            // Create User first as per backend logic
            $user = \App\Models\User::create([
                'name'              => $data['name'],
                'email'             => $data['email'] ?? null,
                'phone_number'      => $data['phone'] ?? null, // Backend uses phone_number
                'password'          => \Illuminate\Support\Facades\Hash::make('password'), // Default password
                'email_verified_at' => now(),
            ]);

            // Assign role
            $user->roles()->attach(\App\Enums\RoleEnum::REGISTERED_USER->value, [
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Create QurbanCustomer linked to User
            return QurbanCustomer::create([
                'farm_id'    => $farm->id,
                'user_id'    => $user->id,
                'created_by' => $creatorId,
            ]);
        });
    }

    public function findCustomer($id): QurbanCustomer
    {
        return QurbanCustomer::findOrFail($id);
    }

    public function updateCustomer($id, array $data): QurbanCustomer
    {
        $customer = $this->findCustomer($id);

        return DB::transaction(function () use ($customer, $data) {
            // Update the related User model
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

    public function deleteCustomer($id): void
    {
        $customer = $this->findCustomer($id);

        DB::transaction(function () use ($customer) {
            $customer->delete();
        });
    }

    public function listAddresses($customerId)
    {
        return QurbanCustomerAddress::where('qurban_customer_id', $customerId)->get();
    }

    public function storeAddress($farm, $customerId, array $data): QurbanCustomerAddress
    {
        return DB::transaction(function () use ($farm, $customerId, $data) {
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
        });
    }

    public function findAddress($id): QurbanCustomerAddress
    {
        return QurbanCustomerAddress::findOrFail($id);
    }

    public function updateAddress($id, array $data): QurbanCustomerAddress
    {
        $address = $this->findAddress($id);

        return DB::transaction(function () use ($address, $data) {
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
        });
    }

    public function deleteAddress($id): void
    {
        $address = $this->findAddress($id);

        DB::transaction(function () use ($address) {
            $address->delete();
        });
    }
}