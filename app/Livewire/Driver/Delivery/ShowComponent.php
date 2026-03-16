<?php

namespace App\Livewire\Driver\Delivery;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Services\Web\Driver\DriverDeliveryService;

class ShowComponent extends Component
{
    use WithFileUploads;

    public $instructionId;
    public $instruction;
    public $receiptPhoto;

    public function mount($id, DriverDeliveryService $service)
    {
        $this->instructionId = $id;
        $userId = auth()->id();

        $instructions = $service->getDeliveryInstructions($userId, []);
        $this->instruction = $instructions->where('id', $id)->first();

        if (!$this->instruction) {
            abort(404, 'Instruksi pengiriman tidak ditemukan.');
        }
    }

    public function startDelivery(DriverDeliveryService $service)
    {
        try {
            $userId = auth()->id();
            $service->setToInDelivery($userId, $this->instructionId);
            session()->flash('success', 'Pengiriman dimulai.');
            return redirect()->route('driver.delivery.show', $this->instructionId);
        } catch (\Throwable $e) {
            report($e);
            session()->flash('error', 'Terjadi kesalahan pada sistem.');
        }
    }

    public function completeDelivery(DriverDeliveryService $service)
    {
        try {
            $userId = auth()->id();
            $service->setToDelivered($userId, $this->instructionId);
            session()->flash('success', 'Pengiriman selesai.');
            return redirect()->route('driver.delivery.index');
        } catch (\Throwable $e) {
            report($e);
            session()->flash('error', 'Terjadi kesalahan pada sistem.');
        }
    }

    public function uploadPhoto(DriverDeliveryService $service)
    {
        $this->validate([
            'receiptPhoto' => 'required|image|max:2048',
        ]);

        try {
            $userId = auth()->id();

            foreach ($this->instruction->deliveryOrders as $order) {
                $service->uploadReceiptPhoto($userId, $order->id, $this->receiptPhoto);
            }

            session()->flash('success', 'Foto berhasil diupload.');
            $this->receiptPhoto = null;
        } catch (\Throwable $e) {
            report($e);
            session()->flash('error', 'Terjadi kesalahan pada sistem.');
        }
    }

    public function render()
    {
        return view('livewire.driver.delivery.show-component');
    }
}
