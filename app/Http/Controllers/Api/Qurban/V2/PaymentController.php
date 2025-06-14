<?php

namespace App\Http\Controllers\Api\Qurban\V2;

use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\QurbanSaleLivestockD;
use App\Models\QurbanSaleLivestockH;
use App\Models\QurbanSaleLivestockPayment;
use App\Http\Requests\Qurban\SaleLivestockPaymentStoreRequest;
use App\Http\Requests\Qurban\SaleLivestockPaymentUpdateRequest;
use App\Http\Resources\Qurban\SaleLivestockPaymentResource;

class PaymentController extends Controller
{
    public function index($farmId)
    {
        $payments = QurbanSaleLivestockPayment::where('farm_id' , $farmId)->get();

        return ResponseHelper::success(SaleLivestockPaymentResource::collection($payments), 'Payments found', 200);
    }

    public function store(SaleLivestockPaymentStoreRequest $request, $farm_id)
    {
        $validated = $request->validated();

        DB::beginTransaction();

        try {
            $qurbanSaleLivestock = QurbanSaleLivestockH::where('qurban_customer_id', $validated['qurban_customer_id'])
                                        ->where('id' , $validated['qurban_sale_livestock_id'])
                                        ->where('farm_id', $farm_id)
                                        ->firstOrFail();

            $payment = QurbanSaleLivestockPayment::create([
                'farm_id'               => $farm_id,
                'transaction_date'      => $validated['transaction_date'],
                'qurban_customer_id'    => $validated['qurban_customer_id'],
                'qurban_sale_livestock_h_id'          => $validated['qurban_sale_livestock_id'],
                'amount'                => $validated['amount'],
                'created_by'           => auth()->user()->id,
            ]);

            // Commit transaksi
            DB::commit();

            return ResponseHelper::success(new SaleLivestockPaymentResource($payment), 'Payment created successfully', 200);
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();

            return ResponseHelper::error('Failed to create Payment: ' . $e->getMessage(), 500);
        }
    }

    public function show($farmId, $id)
    {
        $payment = QurbanSaleLivestockPayment::findOrFail($id);

        return ResponseHelper::success(new SaleLivestockPaymentResource($payment), 'Payment found', 200);
    }

    public function update(SaleLivestockPaymentUpdateRequest $request, $farm_id, $id)
    {
        $validated = $request->validated();

        DB::beginTransaction();

        try {
            $payment = QurbanSaleLivestockPayment::findOrFail($id);

            $qurbanSaleLivestock = QurbanSaleLivestockH::where('qurban_customer_id', $validated['qurban_customer_id'])
                                        ->where('id' , $validated['qurban_sale_livestock_id'])
                                        ->where('farm_id', $farm_id)
                                        ->firstOrFail();

            // Simpan data ke tabel Qurbans
            $payment->update([
                'transaction_date'              => $validated['transaction_date'],
                'qurban_customer_id'            => $validated['qurban_customer_id'],
                'qurban_sale_livestock_h_id'    => $validated['qurban_sale_livestock_id'],
                'amount'                        => $validated['amount'],
            ]);

            // Commit transaksi
            DB::commit();

            return ResponseHelper::success(new SaleLivestockPaymentResource($payment), 'Payment updated successfully', 200);
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();

            return ResponseHelper::error('Failed to update Qurban: ' . $e->getMessage(), 500);
        }
    }

    public function destroy($farm_id, $id)
    {
        $payment = QurbanSaleLivestockPayment::findOrFail($id);

        $payment->delete();

        return ResponseHelper::success(null, 'Payment deleted successfully', 200);
    }

}
