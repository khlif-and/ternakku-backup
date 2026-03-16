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
        $farm = request()->attributes->get('farm');
        $customers = $this->service->listCustomers($farm);

        return view('admin.care_livestock.customer.index', compact('farm', 'customers'));
    }

    public function create($farm_id)
    {
        $farm = request()->attributes->get('farm');

        return view('admin.care_livestock.customer.create', compact('farm'));
    }

    public function store(Request $request, $farm_id)
    {
        $farm = request()->attributes->get('farm');
        $validated = $request->validate([
            'name' => 'required|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
        ]);

        $this->service->createCustomer($farm, $validated, auth()->user()->id);

        return redirect()
            ->route('admin.care-livestock.customer.index', $farm_id)
            ->with('success', 'Customer berhasil ditambahkan.');
    }

    public function show($farm_id, $id)
    {
        $farm = request()->attributes->get('farm');
        $customer = $this->service->getCustomer($farm, $id);

        return view('admin.care_livestock.customer.show', compact('farm', 'customer'));
    }

    public function edit($farm_id, $id)
    {
        $farm = request()->attributes->get('farm');
        $customer = $this->service->getCustomer($farm, $id);

        return view('admin.care_livestock.customer.edit', compact('farm', 'customer'));
    }

    public function update(Request $request, $farm_id, $id)
    {
        $farm = request()->attributes->get('farm');
        $validated = $request->validate([
            'name' => 'required|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
        ]);

        $this->service->updateCustomer($farm, $id, $validated);

        return redirect()
            ->route('admin.care-livestock.customer.index', $farm_id)
            ->with('success', 'Customer berhasil diperbarui.');
    }

    public function destroy($farm_id, $id)
    {
        $farm = request()->attributes->get('farm');
        $this->service->deleteCustomer($farm, $id);

        return back()->with('success', 'Customer berhasil dihapus.');
    }

    public function addressIndex($farm_id, $customer_id)
    {
        $farm = request()->attributes->get('farm');
        $addresses = $this->service->listAddresses($farm, $customer_id);

        return view('admin.care_livestock.customer.address.index', [
            'farm' => $farm,
            'addresses' => $addresses,
            'customerId' => $customer_id,
        ]);
    }

    public function addressCreate($farm_id, $customer_id)
    {
        $farm = request()->attributes->get('farm');

        return view('admin.care_livestock.customer.address.create', [
            'farm' => $farm,
            'customerId' => $customer_id,
        ]);
    }

    public function addressStore(Request $request, $farm_id, $customer_id)
    {
        $farm = request()->attributes->get('farm');
        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'region_id' => 'required|integer',
            'postal_code' => 'nullable|string',
            'address_line' => 'required|string',
            'longitude' => 'nullable|string',
            'latitude' => 'nullable|string',
        ]);

        $this->service->createAddress($farm, $customer_id, $validated);

        return redirect()
            ->route('admin.care-livestock.customer.address.index', [$farm_id, $customer_id])
            ->with('success', 'Alamat berhasil ditambahkan.');
    }

    public function addressEdit($farm_id, $customer_id, $id)
    {
        $farm = request()->attributes->get('farm');
        $address = $this->service->getAddress($farm, $customer_id, $id);

        return view('admin.care_livestock.customer.address.edit', [
            'farm' => $farm,
            'address' => $address,
            'customerId' => $customer_id,
        ]);
    }

    public function addressUpdate(Request $request, $farm_id, $customer_id, $id)
    {
        $farm = request()->attributes->get('farm');
        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'region_id' => 'required|integer',
            'postal_code' => 'nullable|string',
            'address_line' => 'required|string',
            'longitude' => 'nullable|string',
            'latitude' => 'nullable|string',
        ]);

        $this->service->updateAddress($farm, $customer_id, $id, $validated);

        return redirect()
            ->route('admin.care-livestock.customer.address.index', [$farm_id, $customer_id])
            ->with('success', 'Alamat berhasil diperbarui.');
    }

    public function addressDestroy($farm_id, $customer_id, $id)
    {
        $farm = request()->attributes->get('farm');
        $this->service->deleteAddress($farm, $customer_id, $id);

        return back()->with('success', 'Alamat berhasil dihapus.');
    }
}
