<?php

namespace App\Http\Controllers\Admin\CareLivestock\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Web\Farming\Customer\CustomerService;

class CustomerController extends Controller
{
    private $service;

    public function __construct(CustomerService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request, $farm_id)
    {
        return $this->service->index($farm_id, $request);
    }

    public function create($farm_id)
    {
        return $this->service->create($farm_id);
    }

    public function store(Request $request, $farm_id)
    {
        return $this->service->store($request, $farm_id);
    }

    public function edit($farm_id, $id)
    {
        return $this->service->edit($farm_id, $id);
    }

    public function update(Request $request, $farm_id, $id)
    {
        return $this->service->update($request, $farm_id, $id);
    }

    public function destroy($farm_id, $id)
    {
        return $this->service->destroy($farm_id, $id);
    }

    public function addressIndex($farm_id, $customer_id)
    {
        return $this->service->addressIndex($farm_id, $customer_id);
    }

    public function addressCreate($farm_id, $customer_id)
    {
        return $this->service->addressCreate($farm_id, $customer_id);
    }

    public function addressStore(Request $request, $farm_id, $customer_id)
    {
        return $this->service->addressStore($request, $farm_id, $customer_id);
    }

    public function addressEdit($farm_id, $customer_id, $id)
    {
        return $this->service->addressEdit($farm_id, $customer_id, $id);
    }

    public function addressUpdate(Request $request, $farm_id, $customer_id, $id)
    {
        return $this->service->addressUpdate($request, $farm_id, $customer_id, $id);
    }

    public function addressDestroy($farm_id, $customer_id, $id)
    {
        return $this->service->addressDestroy($farm_id, $customer_id, $id);
    }
}
