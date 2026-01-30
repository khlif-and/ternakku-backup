<?php

namespace App\Livewire\Admin\Qurban\Payment;

use Livewire\Component;
use App\Models\Farm;
use App\Models\QurbanCustomer;
use App\Models\QurbanSaleLivestockD;
use App\Services\Web\Qurban\Payment\PaymentCoreService;
use Illuminate\Support\Facades\Log;

class CreateComponent extends Component
{
    public Farm $farm;

    public $transaction_date;
    public $qurban_customer_id;
    public $livestock_id;
    public $amount;

    public $customers = [];
    public $livestocks = [];

    protected function rules()
    {
        return [
            'qurban_customer_id' => 'required',
            'livestock_id' => 'required',
            'transaction_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
        ];
    }

    protected $messages = [
        'transaction_date.required' => 'Tanggal wajib diisi.',
        'qurban_customer_id.required' => 'Pelanggan wajib dipilih.',
        'livestock_id.required' => 'Ternak wajib dipilih.',
        'amount.required' => 'Jumlah bayar wajib diisi.',
    ];

    public function mount(Farm $farm)
    {
        $this->farm = $farm;
        $this->transaction_date = now()->format('Y-m-d');
        $this->customers = QurbanCustomer::with('user')->where('farm_id', $farm->id)->get();
    }

    public function updatedQurbanCustomerId($value)
    {
        $this->livestocks = \App\Models\QurbanSaleLivestockD::whereHas('qurbanSaleLivestockH', function ($q) use ($value) {
            $q->where('qurban_customer_id', $value);
        })->with('livestock.livestockBreed')->get()->pluck('livestock')->flatten();
        
        $this->livestock_id = null;
        $this->breed_name = null;
    }

    public function updatedLivestockId($value)
    {
        // Re-fetch livestocks if they are lost between requests
        if (empty($this->livestocks) && $this->qurban_customer_id) {
             $this->livestocks = \App\Models\QurbanSaleLivestockD::whereHas('qurbanSaleLivestockH', function ($q) {
                $q->where('qurban_customer_id', $this->qurban_customer_id);
            })->with('livestock.livestockBreed')->get()->pluck('livestock')->flatten();
        }

        $livestock = collect($this->livestocks)->where('id', $value)->first();
        $this->breed_name = $livestock ? ($livestock->livestockBreed->name ?? '-') : null;
    }

    public function save(PaymentCoreService $coreService)
    {
        $this->validate();

        try {
            $payment = $coreService->store([
                'farm_id' => $this->farm->id,
                'transaction_date' => $this->transaction_date,
                'qurban_customer_id' => $this->qurban_customer_id,
                'livestock_id' => $this->livestock_id,
                'amount' => $this->amount,
            ]);

            session()->flash('success', 'Data pembayaran berhasil ditambahkan.');
            return redirect()->route('admin.qurban.payment.show', $payment->id);
        } catch (\Throwable $e) {
            Log::error('Payment Create Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.qurban.payment.create-component');
    }
}
