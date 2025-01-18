<?php

namespace App\Http\Controllers\Api\Qurban;

use Illuminate\Http\Request;
use App\Models\QurbanCustomer;
use App\Helpers\ResponseHelper;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\QurbanCustomerAddress;
use App\Http\Resources\Qurban\CustomerResource;
use App\Http\Requests\Qurban\customerStoreRequest;
use App\Http\Requests\Qurban\CustomerUpdateRequest;
use App\Http\Resources\Qurban\CustomerAddressResource;
use App\Http\Requests\Qurban\CustomerAddressStoreRequest;
use App\Http\Requests\Qurban\CustomerAddressUpdateRequest;

class CustomerController extends Controller
{
    public function store(CustomerStoreRequest $request, $farm_id)
    {
        $validated = $request->validated();

        DB::beginTransaction();

        try {
            // Simpan data ke tabel customers
            $customer = QurbanCustomer::create([
                'farm_id'           => $farm_id,
                'name'              => $validated['name'],
                'phone_number'      => $validated['phone_number'],
            ]);

            // Commit transaksi
            DB::commit();

            return ResponseHelper::success(new CustomerResource($customer), 'Customer created successfully', 200);
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();

            return ResponseHelper::error('Failed to create Customer: ' . $e->getMessage(), 500);
        }
    }

    public function show($farm_id, $id)
    {
        $customer = QurbanCustomer::findOrFail($id);

        return ResponseHelper::success(new CustomerResource($customer), 'Customer found', 200);
    }

    public function index($farm_id)
    {
        $customers = QurbanCustomer::all();

        return ResponseHelper::success(CustomerResource::collection($customers), 'customers found', 200);
    }

    public function update(CustomerUpdateRequest $request, $farm_id, $id)
    {
        $validated = $request->validated();

        DB::beginTransaction();

        try {
            $customer = QurbanCustomer::findOrFail($id);

            // Simpan data ke tabel customers
            $customer->update([
                'name'              => $validated['name'],
                'phone_number'      => $validated['phone_number'],
            ]);

            // Commit transaksi
            DB::commit();

            return ResponseHelper::success(new CustomerResource($customer), 'Customer updated successfully', 200);
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();

            return ResponseHelper::error('Failed to update Customer: ' . $e->getMessage(), 500);
        }
    }

    public function addressStore(CustomerAddressStoreRequest $request, $farm_id, $customer_id)
    {
        $validated = $request->validated();

        DB::beginTransaction();

        try {
            $customer = QurbanCustomerAddress::create([
                'farm_id'               => $farm_id,
                'qurban_customer_id'           => $customer_id,
                'description'              => $validated['description'],
                'region_id'      => $validated['region_id'],
                'postal_code'      => $validated['postal_code'],
                'address_line'      => $validated['address_line'],
                'longitude'      => $validated['longitude'],
                'latitude'      => $validated['latitude'],
            ]);

            // Commit transaksi
            DB::commit();

            return ResponseHelper::success(new CustomerAddressResource($customer), 'Address created successfully', 200);
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();

            return ResponseHelper::error('Failed to create Address: ' . $e->getMessage(), 500);
        }
    }

    public function addressShow($farm_id, $customer_id, $id)
    {
        $customer = QurbanCustomerAddress::findOrFail($id);

        return ResponseHelper::success(new CustomerAddressResource($customer), 'Address found', 200);
    }

    public function addressIndex($farm_id, $customer_id)
    {
        $customers = QurbanCustomerAddress::all();

        return ResponseHelper::success(CustomerAddressResource::collection($customers), 'addresses found', 200);
    }

    public function addressUpdate(CustomerAddressUpdateRequest $request, $farm_id, $customer_id, $id)
    {
        $validated = $request->validated();

        DB::beginTransaction();

        try {
            $customer = QurbanCustomerAddress::findOrFail($id);

            // Simpan data ke tabel customers
            $customer->update([
                'description'              => $validated['description'],
                'region_id'      => $validated['region_id'],
                'postal_code'      => $validated['postal_code'],
                'address_line'      => $validated['address_line'],
                'longitude'      => $validated['longitude'],
                'latitude'      => $validated['latitude'],
            ]);

            // Commit transaksi
            DB::commit();

            return ResponseHelper::success(new CustomerAddressResource($customer), 'Address updated successfully', 200);
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();

            return ResponseHelper::error('Failed to update address: ' . $e->getMessage(), 500);
        }
    }

}
