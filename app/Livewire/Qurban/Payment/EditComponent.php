<?php

namespace App\Livewire\Qurban\Payment;

use Livewire\Component;
use App\Models\Farm;
use App\Models\QurbanCustomer;
use App\Models\QurbanSaleLivestockD;
use App\Models\QurbanPayment;
use App\Services\Web\Qurban\Payment\PaymentCoreService;
use Illuminate\Support\Facades\Log;

class EditComponent extends Component
{
    public Farm $farm;
    public QurbanPayment $payment;

    public $transaction_date;
    public $qurban_customer_id;
    public $livestock_id;
    public $breed_name;
    public $amount;

    public $customers = [];
    public $livestocks = [];

    protected function rules()
    {
        return [
            'transaction_date' => 'required|date',
            'qurban_customer_id' => 'required',
            'livestock_id' => 'required',
            'amount' => 'required|numeric|min:0',
        ];
    }

    protected $messages = [
        'transaction_date.required' => 'Tanggal wajib diisi.',
        'qurban_customer_id.required' => 'Pelanggan wajib dipilih.',
        'livestock_id.required' => 'Ternak wajib dipilih.',
        'amount.required' => 'Jumlah bayar wajib diisi.',
    ];

    public function mount(Farm $farm, QurbanPayment $payment)
    {
        $this->farm = $farm;
        $this->payment = $payment;
        $this->customers = QurbanCustomer::with('user')->where('farm_id', $farm->id)->get();

        $this->fillFormData();
    }

    public function fillFormData()
    {
        $this->qurban_customer_id = $this->payment->qurban_customer_id;
        $this->transaction_date = $this->payment->transaction_date;
        $this->amount = $this->payment->amount;

        // Populate livestocks for this customer
        $this->updatedQurbanCustomerId($this->qurban_customer_id);

        $this->livestock_id = $this->payment->livestock_id;

        // Populate breed name
        $this->updatedLivestockId($this->livestock_id);
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
            $coreService->update($this->payment->id, [
                'transaction_date' => $this->transaction_date,
                'qurban_customer_id' => $this->qurban_customer_id,
                'livestock_id' => $this->livestock_id,
                'amount' => $this->amount,
            ]);

            session()->flash('success', 'Data pembayaran berhasil diperbarui.');
            return redirect()->route('admin.qurban.payment.show', $this->payment->id);
        } catch (\Throwable $e) {
            Log::error('Payment Edit Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.qurban.payment.edit-component');
    }
}
