<?php

namespace App\Livewire\Admin\MilkProductionIndividu;

use Livewire\Component;
use App\Models\Farm;
use App\Enums\LivestockSexEnum;
use App\Services\Web\Farming\MilkProductionIndividu\MilkProductionIndividuCoreService;
use Illuminate\Support\Facades\Log;

class CreateComponent extends Component
{
    public Farm $farm;

    public $transaction_date;
    public $livestock_id = '';
    public $milking_shift = '';
    public $milking_time;
    public $milker_name = '';
    public $quantity_liters;
    public $milk_condition = '';
    public $notes;

    protected function rules()
    {
        return [
            'transaction_date' => 'required|date',
            'livestock_id' => 'required|exists:livestocks,id',
            'milking_shift' => 'required|in:morning,afternoon',
            'milking_time' => 'required',
            'milker_name' => 'required|string|max:255',
            'quantity_liters' => 'required|numeric|min:0',
            'milk_condition' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ];
    }

    protected $messages = [
        'transaction_date.required' => 'Tanggal wajib diisi.',
        'livestock_id.required' => 'Ternak wajib dipilih.',
        'milking_shift.required' => 'Shift perah wajib dipilih.',
        'milking_time.required' => 'Waktu perah wajib diisi.',
        'milker_name.required' => 'Nama pemerah wajib diisi.',
        'quantity_liters.required' => 'Volume susu wajib diisi.',
    ];

    public function mount(Farm $farm)
    {
        $this->farm = $farm;
        $this->transaction_date = now()->format('Y-m-d');
        $this->milking_time = now()->format('H:i');
    }

    public function save(MilkProductionIndividuCoreService $coreService)
    {
        $this->validate();

        try {
            $milkProductionIndividu = $coreService->store($this->farm, [
                'transaction_date' => $this->transaction_date,
                'livestock_id' => $this->livestock_id,
                'milking_shift' => $this->milking_shift,
                'milking_time' => $this->milking_time,
                'milker_name' => $this->milker_name,
                'quantity_liters' => $this->quantity_liters,
                'milk_condition' => $this->milk_condition,
                'notes' => $this->notes,
            ]);

            session()->flash('success', 'Data produksi susu individu berhasil disimpan.');

            return redirect()->route('admin.care-livestock.milk-production-individu.show', [
                $this->farm->id,
                $milkProductionIndividu->id
            ]);
        } catch (\Throwable $e) {
            Log::error('MilkProductionIndividu Save Error', [
                'message' => $e->getMessage(),
            ]);
            session()->flash('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $livestocks = $this->farm->livestocks()
            ->where('livestock_sex_id', LivestockSexEnum::BETINA->value)
            ->get();

        return view('livewire.admin.milk-production-individu.create-component', [
            'livestocks' => $livestocks
        ]);
    }
}