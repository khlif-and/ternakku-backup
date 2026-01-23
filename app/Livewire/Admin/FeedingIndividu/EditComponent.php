<?php

namespace App\Livewire\Admin\FeedingIndividu;

use Livewire\Component;
use App\Models\Farm;
use App\Models\FeedingIndividuD;
use App\Services\Web\Farming\FeedingColony\FeedingIndividuCoreService;
use Illuminate\Support\Facades\Log;

class EditComponent extends Component
{
    public Farm $farm;
    public FeedingIndividuD $feedingIndividu;

    public $transaction_date;
    public $livestock_id;
    public $notes;
    public $items = [];

    public $livestocks = [];

    protected function rules()
    {
        return [
            'transaction_date' => 'required|date',
            'livestock_id' => 'required|exists:livestocks,id',
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
        'livestock_id.required' => 'Ternak wajib dipilih.',
        'items.required' => 'Minimal 1 item pakan harus diisi.',
    ];

    public function mount(Farm $farm, FeedingIndividuD $feedingIndividu)
    {
        $this->farm = $farm;
        $this->feedingIndividu = $feedingIndividu;
        
        $this->livestocks = $farm->livestocks()
            ->with(['livestockType:id,name', 'livestockBreed:id,name'])
            ->get()
            ->sortBy(function ($livestock) {
                return $livestock->eartag_number ?? $livestock->eartag ?? $livestock->id;
            });

        $this->fillFormData();
    }

    public function fillFormData()
    {
        $this->transaction_date = $this->feedingIndividu->feedingH?->transaction_date;
        $this->livestock_id = $this->feedingIndividu->livestock_id;
        $this->notes = $this->feedingIndividu->notes;

        $this->items = $this->feedingIndividu->feedingIndividuItems->map(function ($item) {
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

    public function save(FeedingIndividuCoreService $coreService)
    {
        $this->validate();

        try {
            $coreService->update($this->farm, $this->feedingIndividu->id, [
                'transaction_date' => $this->transaction_date,
                'livestock_id' => $this->livestock_id, // Although usually we don't change livestock on edit, passing it just in case logic needs it or validation checks it.
                'notes' => $this->notes,
                'items' => $this->items,
            ]);

            session()->flash('success', 'Data pemberian pakan individu berhasil diperbarui.');
            return redirect()->route('admin.care-livestock.feeding-individu.show', [$this->farm->id, $this->feedingIndividu->id]);
        } catch (\Throwable $e) {
            Log::error('FeedingIndividu Edit Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.feeding-individu.edit-component');
    }
}
