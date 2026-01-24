<?php

namespace App\Livewire\Admin\MilkProductionGlobal;

use Livewire\Component;
use App\Models\Farm;
use App\Services\Web\Farming\MilkProductionGlobal\MilkProductionGlobalCoreService;
use Illuminate\Support\Facades\Log;

class CreateComponent extends Component
{
    public Farm $farm;

    public $transaction_date;
    public $milker_name = '';
    public $milk_condition = '';
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
            'items.*.milking_time' => 'required',
            'items.*.volume' => 'required|numeric|min:0',
        ];
    }

    protected $messages = [
        'transaction_date.required' => 'Tanggal wajib diisi.',
        'milker_name.required' => 'Nama pemerah wajib diisi.',
        'milk_condition.required' => 'Kondisi susu wajib diisi.',
        'items.*.milking_time.required' => 'Waktu perah wajib diisi.',
        'items.*.volume.required' => 'Volume susu wajib diisi.',
    ];

    public function mount(Farm $farm)
    {
        $this->farm = $farm;
        $this->transaction_date = now()->format('Y-m-d');
        $this->milker_name = '';
        $this->milk_condition = '';
        
        $this->addItem();
    }

    public function addItem()
    {
        $this->items[] = [
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

    public function save(MilkProductionGlobalCoreService $coreService)
    {
        $this->validate();

        try {
            $milkProductionGlobal = $coreService->store($this->farm, [
                'transaction_date' => $this->transaction_date,
                'milker_name' => $this->milker_name,
                'milk_condition' => $this->milk_condition,
                'notes' => $this->notes,
                'items' => $this->items,
            ]);

            session()->flash('success', 'Data produksi susu global berhasil disimpan.');
            
            return redirect()->route('admin.care-livestock.milk-production-global.show', [
                $this->farm->id, 
                $milkProductionGlobal->id
            ]);
        } catch (\Throwable $e) {
            Log::error('MilkProductionGlobal Save Error', [
                'message' => $e->getMessage(),
            ]);
            session()->flash('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.milk-production-global.create-component');
    }
}