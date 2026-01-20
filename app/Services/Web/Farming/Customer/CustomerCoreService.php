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

    public function storeCustomer($farm, array $data, $userId)
    {
        return QurbanCustomer::create([
            'farm_id' => $farm->id,
            'name'    => $data['name'],
            'phone'   => $data['phone'] ?? null,
            'email'   => $data['email'] ?? null,
            'user_id' => $userId,
        ]);
    }

    public function findCustomer($id)
    {
        return QurbanCustomer::findOrFail($id);
    }

    public function updateCustomer($id, array $data)
    {
        $customer = $this->findCustomer($id);
        $customer->update([
            'name'  => $data['name'],
            'phone' => $data['phone'] ?? null,
            'email' => $data['email'] ?? null,
        ]);
        return $customer;
    }

    public function deleteCustomer($id)
    {
        $customer = $this->findCustomer($id);
        $customer->delete();
    }

    public function listAddresses($customerId)
    {
        return QurbanCustomerAddress::where('qurban_customer_id', $customerId)->get();
    }

    public function storeAddress($farm, $customerId, array $data)
    {
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

    public function findAddress($id)
    {
        return QurbanCustomerAddress::findOrFail($id);
    }

    public function updateAddress($id, array $data)
    {
        $address = $this->findAddress($id);
        $address->update([
            'name'         => $data['name'],
            'description'  => $data['description'] ?? "",
            'region_id'    => $data['region_id'],
            'postal_code'  => $data['postal_code'] ?? "",
            'address_line' => $data['address_line'],
            'longitude'    => $data['longitude'] ?? null,
            'latitude'     => $data['latitude'] ?? null,
        ]);
        return $address;
    }

    public function deleteAddress($id)
    {
        $address = $this->findAddress($id);
        $address->delete();
    }
}
