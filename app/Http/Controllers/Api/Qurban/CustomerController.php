<?php

namespace App\Http\Controllers\Api\Qurban;

use App\Models\QurbanCustomer;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\Qurban\CustomerResource;
use App\Http\Requests\Qurban\customerStoreRequest;
use App\Http\Requests\Qurban\CustomerUpdateRequest;

class CustomerController extends Controller
{
    public function store(customerStoreRequest $request, $farm_id)
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

    public function show($id)
    {
        $customer = QurbanCustomer::findOrFail($id);

        return ResponseHelper::success(new CustomerResource($customer), 'Customer found', 200);
    }

    public function index()
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
}
