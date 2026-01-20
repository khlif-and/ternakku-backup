<?php

namespace App\Services\Web\Farming\Customer;

use Illuminate\Http\Request;
use App\Exceptions\ErrorHandler;

class CustomerService
{
    protected CustomerCoreService $core;

    public function __construct(CustomerCoreService $core)
    {
        $this->core = $core;
    }

    public function index($farmId, Request $request)
    {
        return ErrorHandler::handle(function () use ($request) {
            $farm = $request->attributes->get('farm');
            $customers = $this->core->listCustomers($farm);
            return view('admin.care_livestock.customer.index', [
                'customers' => $customers,
                'farm_id' => $farm->id,
            ]);
        }, 'Customer Index Error');
    }

    public function create($farmId)
    {
        return view('admin.care_livestock.customer.create', [
            'farm_id' => $farmId,
        ]);
    }

    public function store(Request $request, $farmId)
    {
        return ErrorHandler::handle(function () use ($request, $farmId) {
            $farm = $request->attributes->get('farm');
            $validated = $request->validate([
                'name'   => 'required|string',
                'phone'  => 'nullable|string',
                'email'  => 'nullable|email',
            ]);
            
            $this->core->storeCustomer($farm, $validated, auth()->user()->id);

            return redirect()
                ->route('admin.care-livestock.customer.index', $farmId)
                ->with('success', 'Customer berhasil ditambahkan.');
        }, 'Customer Store Error');
    }

    public function edit($farmId, $id)
    {
        return ErrorHandler::handle(function () use ($farmId, $id) {
            $customer = $this->core->findCustomer($id);
            return view('admin.care_livestock.customer.edit', [
                'customer' => $customer,
                'farm_id' => $farmId,
            ]);
        }, 'Customer Edit Error');
    }

    public function update(Request $request, $farmId, $id)
    {
        return ErrorHandler::handle(function () use ($request, $farmId, $id) {
            $validated = $request->validate([
                'name'  => 'required|string',
                'phone' => 'nullable|string',
                'email' => 'nullable|email',
            ]);

            $this->core->updateCustomer($id, $validated);
            return redirect()
                ->route('admin.care-livestock.customer.index', $farmId)
                ->with('success', 'Customer berhasil diperbarui.');
        }, 'Customer Update Error');
    }

    public function destroy($farmId, $id)
    {
        return ErrorHandler::handle(function () use ($id) {
            $this->core->deleteCustomer($id);
            return back()->with('success', 'Customer berhasil dihapus.');
        }, 'Customer Delete Error');
    }

    public function addressIndex($farmId, $customerId)
    {
        return ErrorHandler::handle(function () use ($farmId, $customerId) {
            $addresses = $this->core->listAddresses($customerId);
            return view('admin.care_livestock.customer.address.index', [
                'addresses' => $addresses,
                'farm_id' => $farmId,
                'customer_id' => $customerId,
            ]);
        }, 'Customer Address Index Error');
    }

    public function addressCreate($farmId, $customerId)
    {
        return view('admin.care_livestock.customer.address.create', [
            'farm_id' => $farmId,
            'customer_id' => $customerId,
        ]);
    }

    public function addressStore(Request $request, $farmId, $customerId)
    {
        return ErrorHandler::handle(function () use ($request, $farmId, $customerId) {
            $farm = $request->attributes->get('farm');
            $validated = $request->validate([
                'name'        => 'required|string',
                'description' => 'nullable|string',
                'region_id'   => 'required|integer',
                'postal_code' => 'nullable|string',
                'address_line'=> 'required|string',
                'longitude'   => 'nullable|string',
                'latitude'    => 'nullable|string',
            ]);

            $this->core->storeAddress($farm, $customerId, $validated);
            return redirect()
                ->route('admin.care-livestock.customer.address.index', [$farmId, $customerId])
                ->with('success', 'Alamat berhasil ditambahkan.');
        }, 'Customer Address Store Error');
    }

    public function addressEdit($farmId, $customerId, $id)
    {
        return ErrorHandler::handle(function () use ($farmId, $customerId, $id) {
            $address = $this->core->findAddress($id);
            return view('admin.care_livestock.customer.address.edit', [
                'address' => $address,
                'farm_id' => $farmId,
                'customer_id' => $customerId,
            ]);
        }, 'Customer Address Edit Error');
    }

    public function addressUpdate(Request $request, $farmId, $customerId, $id)
    {
        return ErrorHandler::handle(function () use ($request, $farmId, $customerId, $id) {
            $validated = $request->validate([
                'name'        => 'required|string',
                'description' => 'nullable|string',
                'region_id'   => 'required|integer',
                'postal_code' => 'nullable|string',
                'address_line'=> 'required|string',
                'longitude'   => 'nullable|string',
                'latitude'    => 'nullable|string',
            ]);

            $this->core->updateAddress($id, $validated);
            return redirect()
                ->route('admin.care-livestock.customer.address.index', [$farmId, $customerId])
                ->with('success', 'Alamat berhasil diperbarui.');
        }, 'Customer Address Update Error');
    }

    public function addressDestroy($farmId, $customerId, $id)
    {
        return ErrorHandler::handle(function () use ($id) {
            $this->core->deleteAddress($id);
            return back()->with('success', 'Alamat berhasil dihapus.');
        }, 'Customer Address Delete Error');
    }
}
