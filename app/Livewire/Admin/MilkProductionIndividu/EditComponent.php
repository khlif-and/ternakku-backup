<?php

namespace App\Livewire\Admin\MilkProductionIndividu;

use Livewire\Component;
use App\Models\Farm;
use App\Models\MilkProductionIndividuD;
use App\Enums\LivestockSexEnum;
use App\Services\Web\Farming\MilkProductionIndividu\MilkProductionIndividuCoreService;
use Illuminate\Support\Facades\Log;

class EditComponent extends Component
{
    public Farm $farm;
    public MilkProductionIndividuD $milkProductionIndividu;

    public $transaction_date;
    public $livestock_id;
    public $milking_shift;
    public $milking_time;
    public $milker_name;
    public $quantity_liters;
    public $milk_condition;
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

    public function mount(Farm $farm, MilkProductionIndividuD $milkProductionIndividu)
    {
        $this->farm = $farm;
        $this->milkProductionIndividu = $milkProductionIndividu;
        $this->fillFormData();
    }

    public function fillFormData()
    {
        $this->transaction_date = $this->milkProductionIndividu->milkProductionH?->transaction_date;
        $this->livestock_id = $this->milkProductionIndividu->livestock_id;
        $this->milking_shift = $this->milkProductionIndividu->milking_shift;
        $this->milking_time = $this->milkProductionIndividu->milking_time ? date('H:i', strtotime($this->milkProductionIndividu->milking_time)) : '';
        $this->milker_name = $this->milkProductionIndividu->milker_name;
        $this->quantity_liters = $this->milkProductionIndividu->quantity_liters;
        $this->milk_condition = $this->milkProductionIndividu->milk_condition;
        $this->notes = $this->milkProductionIndividu->notes;
    }

    public function save(MilkProductionIndividuCoreService $coreService)
    {
        $this->validate();

        try {
            $coreService->update($this->farm, $this->milkProductionIndividu->id, [
                'transaction_date' => $this->transaction_date,
                'livestock_id' => $this->livestock_id,
                'milking_shift' => $this->milking_shift,
                'milking_time' => $this->milking_time,
                'milker_name' => $this->milker_name,
                'quantity_liters' => $this->quantity_liters,
                'milk_condition' => $this->milk_condition,
                'notes' => $this->notes,
            ]);

            session()->flash('success', 'Data produksi susu individu berhasil diperbarui.');
            return redirect()->route('admin.care-livestock.milk-production-individu.show', [$this->farm->id, $this->milkProductionIndividu->id]);
        } catch (\Throwable $e) {
            report($e);
            session()->flash('error', 'Terjadi kesalahan pada sistem.');
        }
    }

    public function render()
    {
        $livestocks = $this->farm->livestocks()
            ->where('livestock_sex_id', LivestockSexEnum::BETINA->value)
            ->get();

        return view('livewire.admin.milk-production-individu.edit-component', [
            'livestocks' => $livestocks
        ]);
    }
}