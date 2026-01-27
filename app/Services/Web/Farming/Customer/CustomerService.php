<?php

namespace App\Services\Web\Farming\Customer;

use Illuminate\Http\Request;

class CustomerService
{
    protected CustomerCoreService $core;

    public function __construct(CustomerCoreService $core)
    {
        $this->core = $core;
    }

    public function index($farmId, Request $request)
    {
        $farm = request()->attributes->get('farm');
        $customers = $this->core->listCustomers($farm);

        return view('admin.care_livestock.customer.index', compact('farm', 'customers'));
    }

    public function create($farmId)
    {
        $farm = request()->attributes->get('farm');

        return view('admin.care_livestock.customer.create', compact('farm'));
    }

    public function store(Request $request, $farmId)
    {
        $farm = request()->attributes->get('farm');
        $validated = $request->validate([
            'name'   => 'required|string',
            'phone'  => 'nullable|string',
            'email'  => 'nullable|email',
        ]);

        $this->core->storeCustomer($farm, $validated, auth()->user()->id);

        return redirect()
            ->route('admin.care-livestock.customer.index', $farmId)
            ->with('success', 'Customer berhasil ditambahkan.');
    }

    public function show($farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        $customer = $this->core->findCustomer($id);

        return view('admin.care_livestock.customer.show', compact('farm', 'customer'));
    }

    public function edit($farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        $customer = $this->core->findCustomer($id);

        return view('admin.care_livestock.customer.edit', compact('farm', 'customer'));
    }

    public function update(Request $request, $farmId, $id)
    {
        $validated = $request->validate([
            'name'  => 'required|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
        ]);

        $this->core->updateCustomer($id, $validated);

        return redirect()
            ->route('admin.care-livestock.customer.index', $farmId)
            ->with('success', 'Customer berhasil diperbarui.');
    }

    public function destroy($farmId, $id)
    {
        $this->core->deleteCustomer($id);

        return back()->with('success', 'Customer berhasil dihapus.');
    }

    public function addressIndex($farmId, $customerId)
    {
        $farm = request()->attributes->get('farm');
        $addresses = $this->core->listAddresses($customerId);

        return view('admin.care_livestock.customer.address.index', compact('farm', 'addresses', 'customerId'));
    }

    public function addressCreate($farmId, $customerId)
    {
        $farm = request()->attributes->get('farm');

        return view('admin.care_livestock.customer.address.create', compact('farm', 'customerId'));
    }

    public function addressStore(Request $request, $farmId, $customerId)
    {
        $farm = request()->attributes->get('farm');
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
    }

    public function addressEdit($farmId, $customerId, $id)
    {
        $farm = request()->attributes->get('farm');
        $address = $this->core->findAddress($id);

        return view('admin.care_livestock.customer.address.edit', compact('farm', 'address', 'customerId'));
    }

    public function addressUpdate(Request $request, $farmId, $customerId, $id)
    {
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
    }

    public function addressDestroy($farmId, $customerId, $id)
    {
        $this->core->deleteAddress($id);

        return back()->with('success', 'Alamat berhasil dihapus.');
    }
}