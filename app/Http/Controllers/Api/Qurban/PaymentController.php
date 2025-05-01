<?php

namespace App\Http\Controllers\Api\Qurban;

use Illuminate\Http\Request;
use App\Models\QurbanPayment;
use App\Helpers\ResponseHelper;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\QurbanSaleLivestockD;
use App\Http\Resources\Qurban\PaymentResource;
use App\Http\Requests\Qurban\PaymentStoreRequest;
use App\Http\Requests\Qurban\PaymentUpdateRequest;

class PaymentController extends Controller
{
    public function index($farmId)
    {
        $payments = QurbanPayment::where('farm_id' , $farmId)->get();

        return ResponseHelper::success(PaymentResource::collection($payments), 'Payments found', 200);
    }

    public function store(PaymentStoreRequest $request, $farm_id)
    {
        $validated = $request->validated();

        DB::beginTransaction();

        try {
            $qurbanSaleLivestock = QurbanSaleLivestockD::where('livestock_id', $validated['livestock_id'])
                                        ->whereHas('qurbanSaleLivestockH', function ($q) use ($validated) {
                                            $q->where('qurban_customer_id', $validated['qurban_customer_id']);
                                        })
                                        ->firstOrFail();

            $payment = QurbanPayment::create([
                'farm_id'               => $farm_id,
                'transaction_date'      => $validated['transaction_date'],
                'qurban_customer_id'    => $validated['qurban_customer_id'],
                'livestock_id'          => $validated['livestock_id'],
                'amount'                => $validated['amount'],
                'created_by'           => auth()->user()->id,
            ]);

            // Commit transaksi
            DB::commit();

            return ResponseHelper::success(new PaymentResource($payment), 'Payment created successfully', 200);
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();

            return ResponseHelper::error('Failed to create Payment: ' . $e->getMessage(), 500);
        }
    }

    public function show($farmId, $id)
    {
        $payment = QurbanPayment::findOrFail($id);

        return ResponseHelper::success(new PaymentResource($payment), 'Payment found', 200);
    }

    public function update(PaymentUpdateRequest $request, $farm_id, $id)
    {
        $validated = $request->validated();

        DB::beginTransaction();

        try {
            $payment = QurbanPayment::findOrFail($id);

            $qurbanSaleLivestock = QurbanSaleLivestockD::where('livestock_id', $validated['livestock_id'])
                                        ->whereHas('qurbanSaleLivestockH', function ($q) use ($validated) {
                                            $q->where('qurban_customer_id', $validated['qurban_customer_id']);
                                        })
                                        ->firstOrFail();


            // Simpan data ke tabel Qurbans
            $payment->update([
                'transaction_date'      => $validated['transaction_date'],
                'qurban_customer_id'    => $validated['qurban_customer_id'],
                'livestock_id'          => $validated['livestock_id'],
                'amount'                => $validated['amount'],
            ]);

            // Commit transaksi
            DB::commit();

            return ResponseHelper::success(new PaymentResource($payment), 'Payment updated successfully', 200);
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();

            return ResponseHelper::error('Failed to update Qurban: ' . $e->getMessage(), 500);
        }
    }

    public function destroy($farm_id, $id)
    {
        $payment = QurbanPayment::findOrFail($id);

        $payment->delete();

        return ResponseHelper::success(null, 'Payment deleted successfully', 200);
    }

}
