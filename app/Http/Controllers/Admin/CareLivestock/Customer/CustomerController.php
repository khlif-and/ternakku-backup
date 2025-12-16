<?php

namespace App\Http\Controllers\Admin\CareLivestock\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use App\Models\QurbanCustomer;
use App\Models\QurbanCustomerAddress;

use App\Services\Qurban\CustomerService;

class CustomerController extends Controller
{
    private $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    /**
     * LIST DATA CUSTOMER
     */
    public function index(Request $request, $farm_id)
    {
        try {
            $customers = $this->customerService->getCustomers($farm_id);

            return view('admin.care_livestock.customer.index', [
                'customers' => $customers,
                'farm_id'   => $farm_id,
            ]);

        } catch (\Throwable $e) {

            Log::error('Customer Index Error', [
                'msg'  => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * FORM TAMBAH CUSTOMER
     */
    public function create($farm_id)
    {
        try {
            return view('admin.care_livestock.customer.create', [
                'farm_id' => $farm_id,
            ]);
        } catch (\Throwable $e) {

            Log::error('Customer Create Error', [
                'msg'  => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * SIMPAN CUSTOMER
     */
    public function store(Request $request, $farm_id)
    {
        $validated = $request->validate([
            'name'   => 'required|string',
            'phone'  => 'nullable|string',
            'email'  => 'nullable|email',
        ]);

        try {
            QurbanCustomer::create([
                'farm_id' => $farm_id,
                'name'    => $validated['name'],
                'phone'   => $validated['phone'] ?? null,
                'email'   => $validated['email'] ?? null,
                'user_id' => auth()->user()->id,
            ]);

            return redirect()
                ->route('admin.care-livestock.customer.index', $farm_id)
                ->with('success', 'Customer berhasil ditambahkan.');

        } catch (\Throwable $e) {

            Log::error('Customer Store Error', [
                'msg'  => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * FORM EDIT
     */
    public function edit($farm_id, $id)
    {
        try {
            $customer = QurbanCustomer::findOrFail($id);

            return view('admin.care_livestock.customer.edit', [
                'customer' => $customer,
                'farm_id'  => $farm_id,
            ]);

        } catch (\Throwable $e) {

            Log::error('Customer Edit Error', [
                'msg'  => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * UPDATE CUSTOMER
     */
    public function update(Request $request, $farm_id, $id)
    {
        $validated = $request->validate([
            'name'  => 'required|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
        ]);

        try {
            $customer = QurbanCustomer::findOrFail($id);

            $customer->update([
                'name'  => $validated['name'],
                'phone' => $validated['phone'] ?? null,
                'email' => $validated['email'] ?? null,
            ]);

            return redirect()
                ->route('admin.care-livestock.customer.index', $farm_id)
                ->with('success', 'Customer berhasil diperbarui.');

        } catch (\Throwable $e) {

            Log::error('Customer Update Error', [
                'msg'  => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * DELETE CUSTOMER
     */
    public function destroy($farm_id, $id)
    {
        try {
            $customer = QurbanCustomer::findOrFail($id);
            $customer->delete();

            return back()->with('success', 'Customer berhasil dihapus.');

        } catch (\Throwable $e) {

            Log::error('Customer Delete Error', [
                'msg'  => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * =========================
     *  ADDRESS SECTION (WEB)
     * =========================
     */

    public function addressIndex($farm_id, $customer_id)
    {
        try {
            $addresses = QurbanCustomerAddress::where('qurban_customer_id', $customer_id)->get();

            return view('admin.care_livestock.customer.address.index', [
                'addresses'   => $addresses,
                'farm_id'     => $farm_id,
                'customer_id' => $customer_id,
            ]);

        } catch (\Throwable $e) {

            Log::error('Customer Address Index Error', [
                'msg' => $e->getMessage(),
            ]);

            return back()->with('error', $e->getMessage());
        }
    }

    public function addressCreate($farm_id, $customer_id)
    {
        return view('admin.care_livestock.customer.address.create', [
            'farm_id'     => $farm_id,
            'customer_id' => $customer_id,
        ]);
    }

    public function addressStore(Request $request, $farm_id, $customer_id)
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

        try {
            QurbanCustomerAddress::create([
                'farm_id'            => $farm_id,
                'qurban_customer_id' => $customer_id,
                'name'               => $validated['name'],
                'description'        => $validated['description'] ?? '',
                'region_id'          => $validated['region_id'],
                'postal_code'        => $validated['postal_code'] ?? '',
                'address_line'       => $validated['address_line'],
                'longitude'          => $validated['longitude'] ?? null,
                'latitude'           => $validated['latitude'] ?? null,
            ]);

            return redirect()
                ->route('admin.care-livestock.customer.address.index', [$farm_id, $customer_id])
                ->with('success', 'Alamat berhasil ditambahkan.');

        } catch (\Throwable $e) {

            Log::error('Customer Address Store Error', [
                'msg' => $e->getMessage(),
            ]);

            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function addressEdit($farm_id, $customer_id, $id)
    {
        try {
            $address = QurbanCustomerAddress::findOrFail($id);

            return view('admin.care_livestock.customer.address.edit', [
                'address'     => $address,
                'farm_id'     => $farm_id,
                'customer_id' => $customer_id,
            ]);

        } catch (\Throwable $e) {

            Log::error('Customer Address Edit Error', [
                'msg' => $e->getMessage(),
            ]);

            return back()->with('error', $e->getMessage());
        }
    }

    public function addressUpdate(Request $request, $farm_id, $customer_id, $id)
    {
        $validated = $request->validate([
            'name'        => 'required|string',
            'description' => 'nullable|string',
            'region_id'   => 'required',
            'postal_code' => 'nullable|string',
            'address_line'=> 'required|string',
            'longitude'   => 'nullable|string',
            'latitude'    => 'nullable|string',
        ]);

        try {
            $address = QurbanCustomerAddress::findOrFail($id);

            $address->update([
                'name'         => $validated['name'],
                'description'  => $validated['description'] ?? "",
                'region_id'    => $validated['region_id'],
                'postal_code'  => $validated['postal_code'] ?? "",
                'address_line' => $validated['address_line'],
                'longitude'    => $validated['longitude'] ?? null,
                'latitude'     => $validated['latitude'] ?? null,
            ]);

            return redirect()
                ->route('admin.care-livestock.customer.address.index', [$farm_id, $customer_id])
                ->with('success', 'Alamat berhasil diperbarui.');

        } catch (\Throwable $e) {

            Log::error('Customer Address Update Error', [
                'msg' => $e->getMessage(),
            ]);

            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function addressDestroy($farm_id, $customer_id, $id)
    {
        try {
            $address = QurbanCustomerAddress::findOrFail($id);
            $address->delete();

            return back()->with('success', 'Alamat berhasil dihapus.');

        } catch (\Throwable $e) {

            Log::error('Customer Address Delete Error', [
                'msg' => $e->getMessage(),
            ]);

            return back()->with('error', $e->getMessage());
        }
    }
}
