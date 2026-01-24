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
    public $milker_name;
    public $milk_condition;
    public $notes;
    public $items = [];

    protected function rules()
    {
        return [
            'transaction_date' => 'required|date',
            'milker_name' => 'required|string|max:255',
            'milk_condition' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.livestock_id' => 'required|exists:livestocks,id',
            'items.*.milking_time' => 'required',
            'items.*.volume' => 'required|numeric|min:0',
        ];
    }

    protected $messages = [
        'transaction_date.required' => 'Tanggal wajib diisi.',
        'milker_name.required' => 'Nama pemerah wajib diisi.',
        'milk_condition.required' => 'Kondisi susu wajib diisi.',
        'items.*.livestock_id.required' => 'Ternak wajib dipilih.',
        'items.*.milking_time.required' => 'Waktu perah wajib diisi.',
        'items.*.volume.required' => 'Volume susu wajib diisi.',
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
        $this->milker_name = $this->milkProductionIndividu->milker_name;
        $this->milk_condition = $this->milkProductionIndividu->milk_condition;
        $this->notes = $this->milkProductionIndividu->notes;

        $this->items = [
            [
                'livestock_id' => $this->milkProductionIndividu->livestock_id,
                'milking_time' => $this->milkProductionIndividu->milking_time ? date('H:i', strtotime($this->milkProductionIndividu->milking_time)) : now()->format('H:i'),
                'volume' => $this->milkProductionIndividu->quantity_liters,
            ]
        ];
    }

    public function addItem()
    {
        $this->items[] = [
            'livestock_id' => '',
            'milking_time' => now()->format('H:i'),
            'volume' => 0,
        ];
    }

    public function removeItem($index)
    {
        if (count($this->items) > 1) {
            unset($this->items[$index]);
            $this->items = array_values($this->items);
        }
    }

    public function save(MilkProductionIndividuCoreService $coreService)
    {
        $this->validate();

        try {
            $coreService->update($this->farm, $this->milkProductionIndividu->id, [
                'transaction_date' => $this->transaction_date,
                'milker_name' => $this->milker_name,
                'milk_condition' => $this->milk_condition,
                'notes' => $this->notes,
                'items' => $this->items,
            ]);

            session()->flash('success', 'Data produksi susu individu berhasil diperbarui.');
            return redirect()->route('admin.care-livestock.milk-production-individu.show', [$this->farm->id, $this->milkProductionIndividu->id]);
        } catch (\Throwable $e) {
            Log::error('MilkProductionIndividu Edit Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
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