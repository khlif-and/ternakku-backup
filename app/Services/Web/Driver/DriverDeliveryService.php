<?php

namespace App\Services\Web\Driver;

use App\Services\Qurban\DeliveryInstructionService;
use App\Services\Qurban\DeliveryOrderService;

class DriverDeliveryService
{
    protected $deliveryInstructionService;
    protected $deliveryOrderService;

    public function __construct(
        DeliveryInstructionService $deliveryInstructionService,
        DeliveryOrderService $deliveryOrderService
    ) {
        $this->deliveryInstructionService = $deliveryInstructionService;
        $this->deliveryOrderService = $deliveryOrderService;
    }

    public function getDeliveryInstructions($userId, array $params = [])
    {
        return $this->deliveryInstructionService->getDeliveryInstructionForDriver($userId, $params);
    }

    public function setToInDelivery($userId, $id)
    {
        return $this->deliveryInstructionService->setToInDelivery($userId, $id);
    }

    public function setToDelivered($userId, $id)
    {
        return $this->deliveryInstructionService->setToDelivered($userId, $id);
    }

    public function storeLocation($userId, $id, array $data)
    {
        return $this->deliveryInstructionService->storeDriverLocation($userId, $id, $data);
    }

    public function uploadReceiptPhoto($userId, $id, $photo)
    {
        return $this->deliveryOrderService->uploadReceiptPhoto($userId, $id, $photo);
    }

    public function getDriverStats($userId)
    {
        $instructions = $this->getDeliveryInstructions($userId, []);

        return [
            'total' => $instructions->count(),
            'scheduled' => $instructions->where('status', 'scheduled')->count(),
            'ready_to_deliver' => $instructions->where('status', 'ready_to_deliver')->count(),
            'in_delivery' => $instructions->where('status', 'in_delivery')->count(),
            'delivered' => $instructions->where('status', 'delivered')->count(),
        ];
    }
}
