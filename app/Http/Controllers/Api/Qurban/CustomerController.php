<?php

namespace App\Http\Controllers\Api\Qurban;

use Illuminate\Http\Request;
use App\Models\QurbanPayment;
use App\Models\QurbanCustomer;
use App\Helpers\ResponseHelper;
use App\Models\QurbanSalesOrder;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\QurbanSaleLivestockD;
use App\Models\QurbanSaleLivestockH;
use App\Models\QurbanCustomerAddress;
use App\Http\Requests\RegisterRequest;
use App\Services\Qurban\CustomerService;
use App\Services\Qurban\SalesOrderService;
use App\Http\Resources\Qurban\PaymentResource;
use App\Http\Resources\Qurban\CustomerResource;
use App\Http\Resources\Qurban\SalesOrderResource;
use App\Http\Requests\Qurban\CustomerStoreRequest;
use App\Http\Requests\Qurban\CustomerUpdateRequest;
use App\Http\Resources\Qurban\SalesLivestockResource;
use App\Http\Resources\Qurban\CustomerAddressResource;
use App\Http\Requests\Qurban\CustomerAddressStoreRequest;
use App\Http\Requests\Qurban\CustomerAddressUpdateRequest;

class CustomerController extends Controller
{
    private $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    public function store(CustomerStoreRequest $request, $farm_id)
    {
        $validated = $request->validated();

        $response = $this->customerService->storeCustomer($validated, $farm_id);

        if($response['error']) {
            return ResponseHelper::error('Failed to create Customer', 500);
        }

        return ResponseHelper::success(new CustomerResource($response['data']), 'Customer created successfully', 200);
    }

    public function show($farm_id, $id)
    {

        $customer = $this->customerService->getCustomer($farm_id, $id);

        return ResponseHelper::success(new CustomerResource($customer), 'Customer found', 200);
    }

    public function index($farm_id)
    {
        $customers = $this->customerService->getCustomers($farm_id);

        return ResponseHelper::success(CustomerResource::collection($customers), 'customers found', 200);
    }

    public function newUser(RegisterRequest $request, $farm_id)
    {
        $validated = $request->validated();

        $customer = $this->customerService->newUser($validated, $farm_id);

        if($customer['error']) {
            return ResponseHelper::error('Failed to create Customer', 500);
        }

        return ResponseHelper::success(new CustomerResource($customer), 'Customer created successfully', 200);
    }

    // public function update(CustomerUpdateRequest $request, $farm_id, $id)
    // {
    //     $validated = $request->validated();

    //     $response = $this->customerService->updateCustomer($validated, $farm_id, $id);

    //     if($response['error']) {
    //         return ResponseHelper::error('Failed to update Customer', 500);
    //     }

    //     return ResponseHelper::success(new CustomerResource($response['data']), 'Customer updated successfully', 200);
    // }

    public function destroy($farm_id, $id)
    {
        $response = $this->customerService->deleteCustomer($farm_id, $id);

        if($response['error']) {
            return ResponseHelper::error('Failed to delete Customer', 500);
        }

        return ResponseHelper::success(null, 'Customer deleted successfully', 200);
    }

    public function addressStore(CustomerAddressStoreRequest $request, $farm_id, $customer_id)
    {
        $validated = $request->validated();

        DB::beginTransaction();

        try {
            $customer = QurbanCustomerAddress::create([
                'name'               => $validated['name'],
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
        $response = $this->customerService->getAddresses($farm_id, $customer_id);

        return ResponseHelper::success(CustomerAddressResource::collection($response), 'addresses found', 200);
    }

    public function addressUpdate(CustomerAddressUpdateRequest $request, $farm_id, $customer_id, $id)
    {
        $validated = $request->validated();

        DB::beginTransaction();

        try {
            $customer = QurbanCustomerAddress::findOrFail($id);

            // Simpan data ke tabel customers
            $customer->update([
                'name'              => $validated['name'],
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

    public function addressDestroy($farm_id, $customer_id, $id)
    {
        DB::beginTransaction();

        try {
            $address = QurbanCustomerAddress::findOrFail($id);

            $address->delete();

            // Commit transaksi
            DB::commit();

            return ResponseHelper::success(null, 'Address deleted successfully', 200);
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();

            return ResponseHelper::error('Failed to delete address: ' . $e->getMessage(), 500);
        }
    }

    public function getSalesOrder(Request $request)
    {
        $user = auth()->user();

        $customers = QurbanCustomer::where('user_id', $user->id);

        if ($request->has('farm_id')) {
            $customers = $customers->where('farm_id', $request->input('farm_id'));
        }

        $customerIds = $customers->pluck('id');

        $salesOrders = QurbanSalesOrder::whereIn('qurban_customer_id', $customerIds)->get();

        return ResponseHelper::success(SalesOrderResource::collection($salesOrders), 'sales orders found', 200);
    }

    public function getSalesLivestock(Request $request)
    {
        $user = auth()->user();

        $customers = QurbanCustomer::where('user_id', $user->id);

        if ($request->has('farm_id')) {
            $customers = $customers->where('farm_id', $request->input('farm_id'));
        }

        $customerIds = $customers->pluck('id');

        $salesOrders = QurbanSaleLivestockH::whereIn('qurban_customer_id', $customerIds)->get();

        return ResponseHelper::success(SalesLivestockResource::collection($salesOrders), 'sales livestock found', 200);
    }

    public function getPayment(Request $request)
    {
        $user = auth()->user();

        $customers = QurbanCustomer::where('user_id', $user->id);

        if ($request->has('farm_id')) {
            $customers = $customers->where('farm_id', $request->input('farm_id'));
        }

        $customerIds = $customers->pluck('id');

        $salesOrders = QurbanSaleLivestockH::whereIn('qurban_customer_id', $customerIds)->get();

                // Ambil semua ID sales order
        $salesOrderIds = $salesOrders->pluck('id');

        // Ambil semua livestock_id dari detail
        $livestockIds = QurbanSaleLivestockD::whereIn('qurban_sale_livestock_h_id', $salesOrderIds)
                        ->pluck('livestock_id');

        // Ambil payment berdasarkan livestock_id
        $payments = QurbanPayment::whereIn('livestock_id', $livestockIds)->get();


        return ResponseHelper::success(PaymentResource::collection($payments), 'payment found', 200);
    }
}
