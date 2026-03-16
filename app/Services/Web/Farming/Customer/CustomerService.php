<?php

namespace App\Services\Web\Farming\Customer;

class CustomerService
{
    protected CustomerCoreService $core;

    public function __construct(CustomerCoreService $core)
    {
        $this->core = $core;
    }

    public function listCustomers($farm)
    {
        return $this->core->listCustomers($farm);
    }

    public function createCustomer($farm, array $data, int $creatorId)
    {
        return $this->core->storeCustomer($farm, $data, $creatorId);
    }

    public function getCustomer($farm, int $id)
    {
        return $this->core->findCustomer($id, $farm->id);
    }

    public function updateCustomer($farm, int $id, array $data)
    {
        return $this->core->updateCustomer($id, $farm->id, $data);
    }

    public function deleteCustomer($farm, int $id): void
    {
        $this->core->deleteCustomer($id, $farm->id);
    }

    public function listAddresses($farm, int $customerId)
    {
        $this->core->findCustomer($customerId, $farm->id);

        return $this->core->listAddresses($customerId, $farm->id);
    }

    public function createAddress($farm, int $customerId, array $data)
    {
        return $this->core->storeAddress($farm, $customerId, $data);
    }

    public function getAddress($farm, int $customerId, int $id)
    {
        $this->core->findCustomer($customerId, $farm->id);

        return $this->core->findAddress($id, $farm->id);
    }

    public function updateAddress($farm, int $customerId, int $id, array $data)
    {
        $this->core->findCustomer($customerId, $farm->id);

        return $this->core->updateAddress($id, $farm->id, $data);
    }

    public function deleteAddress($farm, int $customerId, int $id): void
    {
        $this->core->findCustomer($customerId, $farm->id);
        $this->core->deleteAddress($id, $farm->id);
    }
}