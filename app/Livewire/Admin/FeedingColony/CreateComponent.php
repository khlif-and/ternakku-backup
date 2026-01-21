<?php

namespace App\Livewire\Admin\FeedingColony;

use Livewire\Component;
use App\Models\Farm;
use App\Services\Web\Farming\FeedingColony\FeedingColonyCoreService;
use Illuminate\Support\Facades\Log;

class CreateComponent extends Component
{
    public Farm $farm;
    public $fromPen = null;

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
            'items.*.type' => 'required|string',
            'items.*.name' => 'required|string',
            'items.*.qty_kg' => 'required|numeric|min:0.01',
            'items.*.price_per_kg' => 'required|numeric|min:0',
        ];
    }

    protected $messages = [
        'transaction_date.required' => 'Tanggal wajib diisi.',
        'pen_id.required' => 'Kandang wajib dipilih.',
        'items.required' => 'Minimal 1 item pakan harus diisi.',
        'items.min' => 'Minimal 1 item pakan harus diisi.',
    ];

    public function mount(Farm $farm, $fromPen = null)
    {
        $this->farm = $farm;
        $this->fromPen = $fromPen;
        $this->transaction_date = now()->format('Y-m-d');
        $this->pen_id = $fromPen?->id;
        $this->pens = $farm->pens;
        $this->addItem();
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
            $feedingColonyD = $coreService->store($this->farm, [
                'transaction_date' => $this->transaction_date,
                'pen_id' => $this->pen_id,
                'notes' => $this->notes,
                'items' => $this->items,
            ]);

            session()->flash('success', 'Data pemberian pakan koloni berhasil ditambahkan.');
            return redirect()->route('admin.care-livestock.feeding-colony.show', [$this->farm->id, $feedingColonyD->id]);
        } catch (\Throwable $e) {
            Log::error('FeedingColony Create Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.feeding-colony.create-component');
    }
}
