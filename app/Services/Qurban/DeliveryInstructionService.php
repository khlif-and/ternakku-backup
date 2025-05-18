<?php

namespace App\Services\Qurban;

use App\Models\QurbanDeliveryInstructionH;
use App\Models\QurbanDeliveryInstructionD;
use Illuminate\Support\Facades\DB;
use Exception;

class DeliveryInstructionService
{
    public function storeDeliveryInstruction($farm_id, array $data): array
    {
        DB::beginTransaction();
        try {
            $instruction = QurbanDeliveryInstructionH::create([
                'farm_id' => $farm_id,
                'delivery_date' => $data['delivery_date'],
                'driver_id' => $data['driver_id'],
                'fleet_id' => $data['fleet_id'],
                'status' => 'scheduled',
            ]);

            foreach ($data['delivery_order_ids'] as $orderId) {
                QurbanDeliveryInstructionD::create([
                    'qurban_delivery_instruction_h_id' => $instruction->id,
                    'qurban_delivery_order_h_id' => $orderId,
                ]);
            }

            DB::commit();

            return ['error' => false, 'data' => $instruction];
        } catch (Exception $e) {
            dd($e);
            DB::rollBack();
            return ['error' => true, 'message' => $e->getMessage()];
        }
    }

    public function getDeliveryInstructions($farm_id, array $params)
    {
        $query = QurbanDeliveryInstructionH::where('farm_id' , $farm_id);

        if (!empty($params['status'])) {
            $query->where('status', $params['status']);
        }

        if (!empty($params['driver_id'])) {
            $query->where('driver_id', $params['driver_id']);
        }

        if (!empty($params['fleet_id'])) {
            $query->where('fleet_id', $params['fleet_id']);
        }

        if (!empty($params['delivery_date_start'])) {
            $query->whereDate('delivery_date', '>=', $params['delivery_date_start']);
        }

        if (!empty($params['delivery_date_end'])) {
            $query->whereDate('delivery_date', '<=', $params['delivery_date_end']);
        }

        return $query->latest()->get();
    }

    public function setToReadyToDeliver($farmId, $id)
    {
        $instruction = QurbanDeliveryInstructionH::where('farm_id', $farmId)->findOrFail($id);

        if ($instruction->status !== 'scheduled') {
            throw new \Exception("Only scheduled instructions can be updated to ready_to_deliver");
        }

        $instruction->status = 'ready_to_deliver';
        $instruction->save();

        return $instruction;
    }

    public function getById($farm_id, $id)
    {
        return QurbanDeliveryInstructionH::with(['fleet', 'driver', 'qurbanDeliveryInstructionD.qurbanDeliveryOrderH'])
            ->where('farm_id', $farm_id)
            ->find($id);
    }

}
