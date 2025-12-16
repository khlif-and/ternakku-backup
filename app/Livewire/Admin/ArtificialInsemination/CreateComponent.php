<?php

namespace App\Livewire\Admin\ArtificialInsemination;

use Livewire\Component;
use App\Models\Livestock;
use App\Models\LivestockBreed;
use App\Enums\LivestockSexEnum;
use App\Services\Web\Farming\ArtificialInsemination\ArtificialInseminationService;

class CreateComponent extends Component
{
    public $farm;
    public $livestock_id;
    public $transaction_date;
    public $action_time;
    public $officer_name;
    public $semen_breed_id;
    public $sire_name;
    public $semen_producer;
    public $semen_batch;
    public $cost;
    public $notes;
    public $selectedLivestockTypeId;

    protected $listeners = ['updateTransactionDate' => 'setTransactionDate'];

    protected $rules = [
        'livestock_id' => 'required|exists:livestocks,id',
        'transaction_date' => 'required|date',
        'action_time' => 'required',
        'officer_name' => 'required|string|max:255',
        'semen_breed_id' => 'required|exists:livestock_breeds,id',
        'sire_name' => 'nullable|string|max:255',
        'semen_producer' => 'nullable|string|max:255',
        'semen_batch' => 'nullable|string|max:255',
        'cost' => 'required|numeric|min:0',
        'notes' => 'nullable|string',
    ];

    public function mount($farm)
    {
        $this->farm = $farm;
        $this->transaction_date = now()->format('Y-m-d');
        $this->action_time = now()->format('H:i');
    }

    public function setTransactionDate($payload)
    {
        if (isset($payload['date'])) {
            $this->transaction_date = $payload['date'];
        }
    }

    public function updatedLivestockId($value)
    {
        $livestock = Livestock::find($value);

        if ($livestock) {
            $this->selectedLivestockTypeId = $livestock->livestock_type_id;
            $this->semen_breed_id = '';
        } else {
            $this->selectedLivestockTypeId = null;
        }
    }

    public function save(ArtificialInseminationService $service)
    {
        $this->validate();

        $livestock = Livestock::find($this->livestock_id);

        if (!$livestock) {
            $this->dispatchBrowserEvent('showError', ['message' => 'Ternak tidak ditemukan.']);
            return;
        }

        if ((int) $livestock->livestock_sex_id !== (int) LivestockSexEnum::BETINA->value) {
            $this->dispatchBrowserEvent('showError', ['message' => 'Ternak yang dipilih bukan betina.']);
            return;
        }

        try {
            $service->recordInsemination($livestock, $this->farm, [
                'livestock_id' => $this->livestock_id,
                'transaction_date' => $this->transaction_date,
                'action_time' => $this->action_time,
                'officer_name' => $this->officer_name,
                'semen_breed_id' => $this->semen_breed_id,
                'sire_name' => $this->sire_name,
                'semen_producer' => $this->semen_producer,
                'semen_batch' => $this->semen_batch,
                'cost' => $this->cost,
                'notes' => $this->notes,
            ]);

            session()->flash('success', 'Data berhasil disimpan.');

            return redirect()->route('admin.care_livestock.artificial_inseminasi.index', [
                'farm_id' => $this->farm->id
            ]);
        }
        catch (\InvalidArgumentException $e) {
            // Error validasi logika dari service (misal ras tidak cocok)
            $this->addError('semen_breed_id', $e->getMessage());
            $this->dispatchBrowserEvent('showError', ['message' => $e->getMessage()]);
        }
        catch (\Illuminate\Validation\ValidationException $e) {
            // Error validasi Laravel standar
            foreach ($e->errors() as $field => $messages) {
                foreach ($messages as $msg) {
                    $this->addError($field, $msg);
                }
            }
            $this->dispatchBrowserEvent('showError', ['message' => 'Validasi gagal. Periksa kembali input Anda.']);
        }
        catch (\Throwable $e) {
            // Error umum / server / database
            \Log::error('Artificial Insemination Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
            $this->dispatchBrowserEvent('showError', ['message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function render()
    {
        $livestocks = $this->farm->livestocks()
            ->where('livestock_sex_id', LivestockSexEnum::BETINA->value)
            ->with(['livestockType', 'livestockBreed', 'pen'])
            ->get();

        $breeds = LivestockBreed::query()
            ->when($this->selectedLivestockTypeId, function ($q) {
                $q->where('livestock_type_id', $this->selectedLivestockTypeId);
            })
            ->orderBy('name')
            ->get(['id', 'name', 'livestock_type_id']);

        return view('livewire.admin.artificial-insemination.create-component', [
            'livestocks' => $livestocks,
            'breeds' => $breeds,
        ]);
    }
}
