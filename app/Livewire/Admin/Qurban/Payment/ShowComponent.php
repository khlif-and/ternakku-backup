<?php

namespace App\Livewire\Admin\Qurban\Payment;

use Livewire\Component;
use App\Models\Farm;
use App\Models\QurbanPayment;
use App\Services\Web\Qurban\Payment\PaymentCoreService;
use Illuminate\Support\Facades\Log;

class ShowComponent extends Component
{
    public Farm $farm;

    public QurbanPayment $payment;

    public function mount(Farm $farm, QurbanPayment $payment)
    {
        $this->farm = $farm;
        $this->payment = $payment->load([
            'qurbanCustomer'
        ]);
    }

    public function delete(PaymentCoreService $coreService)
    {
        try {
            $coreService->delete($this->payment->id);
            
            session()->flash('success', 'Data pembayaran berhasil dihapus.');
            return redirect()->route('admin.qurban.payment.index', $this->farm->id);
        } catch (\Throwable $e) {
            Log::error('Payment Delete Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.qurban.payment.show-component');
    }
}