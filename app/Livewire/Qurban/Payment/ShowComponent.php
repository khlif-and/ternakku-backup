<?php

namespace App\Livewire\Qurban\Payment;

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
            'qurbanCustomer.user',
            'livestock.livestockBreed',
            'farm'
        ]);
    }

    public function delete(PaymentCoreService $coreService)
    {
        try {
            $coreService->delete($this->payment->id);
            
            session()->flash('success', 'Data pembayaran berhasil dihapus.');
            return redirect()->route('admin.qurban.payment.index', $this->farm->id);
        } catch (\Throwable $e) {
            report($e);
            session()->flash('error', 'Terjadi kesalahan pada sistem.');
        }
    }

    public function render()
    {
        return view('livewire.qurban.payment.show-component');
    }
}