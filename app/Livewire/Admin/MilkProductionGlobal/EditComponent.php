<?php

namespace App\Livewire\Admin\MilkProductionGlobal;

use Livewire\Component;
use App\Models\Farm;
use App\Models\MilkProductionGlobal;
use App\Services\Web\Farming\MilkProductionGlobal\MilkProductionGlobalCoreService;
use Illuminate\Support\Facades\Log;

class EditComponent extends Component
{
    public Farm $farm;
    public MilkProductionGlobal $milkProductionGlobal;

    public $transaction_date;
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
        'milking_shift.required' => 'Shift perah wajib dipilih.',
        'milking_time.required' => 'Waktu perah wajib diisi.',
        'milker_name.required' => 'Nama pemerah wajib diisi.',
        'quantity_liters.required' => 'Volume susu wajib diisi.',
    ];

    public function mount(Farm $farm, MilkProductionGlobal $milkProductionGlobal)
    {
        $this->farm = $farm;
        $this->milkProductionGlobal = $milkProductionGlobal;
        $this->fillFormData();
    }

    public function fillFormData()
    {
        $this->transaction_date = $this->milkProductionGlobal->transaction_date;
        $this->milking_shift = $this->milkProductionGlobal->milking_shift;
        $this->milking_time = $this->milkProductionGlobal->milking_time ? date('H:i', strtotime($this->milkProductionGlobal->milking_time)) : '';
        $this->milker_name = $this->milkProductionGlobal->milker_name;
        $this->quantity_liters = $this->milkProductionGlobal->quantity_liters;
        $this->milk_condition = $this->milkProductionGlobal->milk_condition;
        $this->notes = $this->milkProductionGlobal->notes;
    }

    public function save(MilkProductionGlobalCoreService $coreService)
    {
        $this->validate();

        try {
            $coreService->update($this->farm, $this->milkProductionGlobal->id, [
                'transaction_date' => $this->transaction_date,
                'milking_shift' => $this->milking_shift,
                'milking_time' => $this->milking_time,
                'milker_name' => $this->milker_name,
                'quantity_liters' => $this->quantity_liters,
                'milk_condition' => $this->milk_condition,
                'notes' => $this->notes,
            ]);

            session()->flash('success', 'Data produksi susu global berhasil diperbarui.');
            return redirect()->route('admin.care-livestock.milk-production-global.show', [$this->farm->id, $this->milkProductionGlobal->id]);
        } catch (\Throwable $e) {
            Log::error('MilkProductionGlobal Edit Error', [
                'message' => $e->getMessage(),
            ]);
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.milk-production-global.edit-component');
    }
}