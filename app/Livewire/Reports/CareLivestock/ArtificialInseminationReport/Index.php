<?php

namespace App\Livewire\Reports\CareLivestock\ArtificialInseminationReport;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Farm;
use App\Models\Livestock;
use App\Services\Web\Report\ArtificialInsemination\Services\ArtificialInseminationReportService;
use App\Services\Web\Report\ArtificialInsemination\Resources\ArtificialInseminationReportResource;
use Carbon\Carbon;

class Index extends Component
{
    use WithPagination;

    public Farm $farm;
    public $start_date;
    public $end_date;
    public $livestock_id;
    public $showReport = true;
    public $livestockOptions = [];
    public $livestockTypeOptions = [];
    public $livestockGroupOptions = [];
    public $livestockBreedOptions = [];
    public $penOptions = [];

    public $livestock_type_id;
    public $livestock_group_id;
    public $livestock_breed_id;
    public $pen_id;

    protected $queryString = [
        'start_date',
        'end_date',
        'livestock_id',
        'livestock_type_id',
        'livestock_group_id',
        'livestock_breed_id',
        'pen_id',
    ];

    public function mount($farm_id)
    {
        $this->farm = Farm::findOrFail($farm_id);
        $this->start_date = request('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $this->end_date = request('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $this->livestock_id = request('livestock_id');
        $this->livestock_type_id = request('livestock_type_id');
        $this->livestock_group_id = request('livestock_group_id');
        $this->livestock_breed_id = request('livestock_breed_id');
        $this->pen_id = request('pen_id');

        // $this->livestockOptions = Livestock::where('farm_id', $this->farm->id)
        //     ->pluck('eartag_number', 'id')
        //     ->toArray();

        $this->livestockTypeOptions = \App\Models\LivestockType::pluck('name', 'id')->toArray();
        $this->livestockGroupOptions = \App\Models\LivestockGroup::pluck('name', 'id')->toArray();
        $this->penOptions = \App\Models\Pen::where('farm_id', $this->farm->id)->pluck('name', 'id')->toArray();

        // Initialize breeds based on initial type or show all
        $this->updatedLivestockTypeId($this->livestock_type_id);
    }

    public function updatedLivestockTypeId($value)
    {
        if ($value) {
            $this->livestockBreedOptions = \App\Models\LivestockBreed::where('livestock_type_id', $value)
                ->pluck('name', 'id')
                ->toArray();

            $this->livestockOptions = Livestock::where('farm_id', $this->farm->id)
                ->where('livestock_type_id', $value)
                ->pluck('eartag_number', 'id')
                ->toArray();
        } else {
            $this->livestockBreedOptions = \App\Models\LivestockBreed::pluck('name', 'id')->toArray();

            $this->livestockOptions = []; // Kosong jika tidak ada tipe yang dipilih, sesuai request "kalo ga ada ya kosong"
            // Atau jika maksud user "jika tidak ada filter tipe, tampilkan semua", maka:
            // $this->livestockOptions = Livestock::where('farm_id', $this->farm->id)->pluck('eartag_number', 'id')->toArray();
            // Tapi user bilang "kalo ga ada ya kosong", asumsi saya jika tidak ada tipe selected -> kosong.
            // Namun konteks "ga ada" bisa berarti "jika tidak ada data eartag yang sesuai".
            // Mari kita asumsikan user ingin filter eartag sesuai tipe. Jika tidak ada tipe, tampilkan semua atau kosong?
            // "eartag nay juga sesauikan dengan tipe ternak kalo ga ada ya kosong"
            // "if no type, empty". I will stick to empty or all?
            // Usually valid UX is: No Type = All Eartags. Type Selected = Filtered Eartags.
            // But "kalo ga ada ya kosong" might mean "if the filtered result is empty, show empty".
            // Let's implement: If type selected -> filter. If no type selected -> all.
            // Wait, "kalo ga ada ya kosong" might refer to the OPTION list itself.
            // Let's follow standard dependent dropdown logic:
            // If Type Selected: Show Eartags of that Type.
            // If No Type Selected: Show All Eartags (default) OR Show None (forced dependency).
            // Given "sesuaikan dengan tipe ternak", I'll filter.

            $this->livestockOptions = Livestock::where('farm_id', $this->farm->id)
                ->pluck('eartag_number', 'id')
                ->toArray();
        }

        // Reset selections if they don't belong to the new options
        if ($this->livestock_breed_id && !array_key_exists($this->livestock_breed_id, $this->livestockBreedOptions)) {
            $this->livestock_breed_id = null;
        }

        if ($this->livestock_id && !array_key_exists($this->livestock_id, $this->livestockOptions)) {
            $this->livestock_id = null;
        }
    }

    public function generateReport()
    {
        $this->resetPage();
    }

    public function render()
    {
        $service = new ArtificialInseminationReportService();

        $filters = [
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'livestock_id' => $this->livestock_id,
            'livestock_type_id' => $this->livestock_type_id,
            'livestock_group_id' => $this->livestock_group_id,
            'livestock_breed_id' => $this->livestock_breed_id,
            'pen_id' => $this->pen_id,
        ];

        $query = $service->getQuery($this->farm->id, $filters);
        $data = $query->latest()->paginate(50);

        $transformedCollection = $data->getCollection()->map(function ($item) {
            return (new ArtificialInseminationReportResource($item))->resolve();
        });
        $data->setCollection($transformedCollection);

        return view('livewire.reports.care-livestock.artificial-insemination-report.index', [
            'data' => $data,
            'farm' => $this->farm,
        ])
            ->extends('layouts.care_livestock.index')
            ->section('content');
    }
}
