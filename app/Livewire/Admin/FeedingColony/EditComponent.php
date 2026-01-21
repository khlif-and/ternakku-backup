<?php

namespace App\Livewire\Admin\FeedingColony;

use Livewire\Component;
use App\Models\Farm;
use App\Models\FeedingColonyD;
use App\Services\Web\Farming\FeedingColony\FeedingColonyCoreService;
use Illuminate\Support\Facades\Log;

class EditComponent extends Component
{
    public Farm $farm;
    public FeedingColonyD $feedingColony;

    public $transaction_date;
    public $pen_id;
    public $notes;
    public $items = [];

    public $pens = [];

    protected function rules()
    {
        return [
            'transaction_date' => 'required|date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.type' => 'required|string',
            'items.*.name' => 'required|string',
            'items.*.qty_kg' => 'required|numeric|min:0.01',
            'items.*.price_per_kg' => 'required|numeric|min:0',
        ];
    }

    protected $messages = [
        'transaction_date.required' => 'Tanggal wajib diisi.',
        'items.required' => 'Minimal 1 item pakan harus diisi.',
    ];

    public function mount(Farm $farm, FeedingColonyD $feedingColony)
    {
        $this->farm = $farm;
        $this->feedingColony = $feedingColony;
        $this->pens = $farm->pens;
        $this->fillFormData();
    }

    public function fillFormData()
    {
        $this->transaction_date = $this->feedingColony->feedingH?->transaction_date;
        $this->pen_id = $this->feedingColony->pen_id;
        $this->notes = $this->feedingColony->notes;

        $this->items = $this->feedingColony->feedingColonyItems->map(function ($item) {
            return [
                'type' => $item->type,
                'name' => $item->name,
                'qty_kg' => $item->qty_kg,
                'price_per_kg' => $item->price_per_kg,
            ];
        })->toArray();

        if (empty($this->items)) {
            $this->addItem();
        }
    }

    public function addItem()
    {
        $this->items[] = [
            'type' => 'forage',
            'name' => '',
            'qty_kg' => '',
            'price_per_kg' => '',
        ];
    }

    public function removeItem($index)
    {
        if (count($this->items) > 1) {
            unset($this->items[$index]);
            $this->items = array_values($this->items);
        }
    }

    public function save(FeedingColonyCoreService $coreService)
    {
        $this->validate();

        try {
            $coreService->update($this->farm, $this->feedingColony->id, [
                'transaction_date' => $this->transaction_date,
                'notes' => $this->notes,
                'items' => $this->items,
            ]);

            session()->flash('success', 'Data pemberian pakan koloni berhasil diperbarui.');
            return redirect()->route('admin.care-livestock.feeding-colony.show', [$this->farm->id, $this->feedingColony->id]);
        } catch (\Throwable $e) {
            Log::error('FeedingColony Edit Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.feeding-colony.edit-component');
    }
}
