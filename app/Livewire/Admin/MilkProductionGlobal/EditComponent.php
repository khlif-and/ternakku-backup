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
    public $pen_id;
    public $notes;
    public $items = [];

    public $pens = [];

    protected function rules()
    {
        return [
            'transaction_date' => 'required|date',
            'pen_id' => 'required|exists:pens,id',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.name' => 'required|string',
            'items.*.volume' => 'required|numeric|min:0',
        ];
    }

    protected $messages = [
        'transaction_date.required' => 'Tanggal wajib diisi.',
        'pen_id.required' => 'Kandang wajib dipilih.',
        'items.*.name.required' => 'Nama shift wajib diisi.',
        'items.*.volume.required' => 'Volume susu wajib diisi.',
    ];

    public function mount(Farm $farm, MilkProductionGlobal $milkProductionGlobal)
    {
        $this->farm = $farm;
        $this->milkProductionGlobal = $milkProductionGlobal;
        $this->pens = $farm->pens;
        $this->fillFormData();
    }

    public function fillFormData()
    {
        $this->transaction_date = $this->milkProductionGlobal->milkProductionH?->transaction_date;
        $this->pen_id = $this->milkProductionGlobal->pen_id;
        $this->notes = $this->milkProductionGlobal->notes;

        $this->items = $this->milkProductionGlobal->milkProductionGlobalItems->map(function ($item) {
            return [
                'name' => $item->name,
                'volume' => $item->volume,
            ];
        })->toArray();

        if (empty($this->items)) {
            $this->addItem();
        }
    }

    public function addItem()
    {
        $this->items[] = [
            'name' => '',
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
            $coreService->update($this->farm, $this->milkProductionGlobal->id, [
                'transaction_date' => $this->transaction_date,
                'pen_id' => $this->pen_id,
                'notes' => $this->notes,
                'items' => $this->items,
            ]);

            session()->flash('success', 'Data produksi susu global berhasil diperbarui.');
            return redirect()->route('admin.care-livestock.milk-production-global.show', [$this->farm->id, $this->milkProductionGlobal->id]);
        } catch (\Throwable $e) {
            Log::error('MilkProductionGlobal Edit Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.milk-production-global.edit-component');
    }
}