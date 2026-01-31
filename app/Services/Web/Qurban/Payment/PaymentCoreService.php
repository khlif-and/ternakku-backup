<?php

namespace App\Services\Web\Qurban\Payment;

use App\Models\QurbanPayment;
use App\Models\QurbanSalesOrder;
use Illuminate\Support\Facades\DB;

class PaymentCoreService
{
    public function listPayments(array $filters)
    {
        $query = QurbanPayment::with(['qurbanCustomer.user', 'livestock']);

        if (!empty($filters['start_date'])) {
            $query->where('transaction_date', '>=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $query->where('transaction_date', '<=', $filters['end_date']);
        }

        if (!empty($filters['qurban_customer_id'])) {
            $query->where('qurban_customer_id', $filters['qurban_customer_id']);
        }

        return $query->paginate(10);
    }

    public function store(array $data): QurbanPayment
    {
        // Validation logic mirroring API
        $qurbanSaleLivestock = \App\Models\QurbanSaleLivestockD::where('livestock_id', $data['livestock_id'])
            ->whereHas('qurbanSaleLivestockH', function ($q) use ($data) {
                $q->where('qurban_customer_id', $data['qurban_customer_id']);
            })
            ->firstOrFail();

        return DB::transaction(function () use ($data) {
            $payment = QurbanPayment::create([
                'farm_id' => $data['farm_id'],
                'transaction_date' => $data['transaction_date'],
                'qurban_customer_id' => $data['qurban_customer_id'],
                'livestock_id' => $data['livestock_id'],
                'amount' => $data['amount'],
                'created_by' => auth()->id(),
            ]);

            return $payment;
        });
    }

    public function find($id): QurbanPayment
    {
        return QurbanPayment::with(['qurbanCustomer.user', 'livestock'])->findOrFail($id);
    }

    public function update($id, array $data): QurbanPayment
    {
        $payment = QurbanPayment::findOrFail($id);

        // Validation logic mirroring API
        $qurbanSaleLivestock = \App\Models\QurbanSaleLivestockD::where('livestock_id', $data['livestock_id'])
            ->whereHas('qurbanSaleLivestockH', function ($q) use ($data) {
                $q->where('qurban_customer_id', $data['qurban_customer_id']);
            })
            ->firstOrFail();

        return DB::transaction(function () use ($payment, $data) {
            $payment->update([
                'transaction_date' => $data['transaction_date'],
                'qurban_customer_id' => $data['qurban_customer_id'],
                'livestock_id' => $data['livestock_id'],
                'amount' => $data['amount'],
            ]);

            return $payment;
        });
    }

    public function delete($id): void
    {
        $payment = QurbanPayment::findOrFail($id);
        $payment->delete();
    }
}
