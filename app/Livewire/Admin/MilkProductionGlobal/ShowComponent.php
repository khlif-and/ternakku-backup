<?php

namespace App\Livewire\Admin\MilkProductionGlobal;

use Livewire\Component;
use App\Models\Farm;
use App\Models\MilkProductionGlobal;

class ShowComponent extends Component
{
    public Farm $farm;
    public MilkProductionGlobal $milkProductionGlobal;

    public function mount(Farm $farm, MilkProductionGlobal $milkProductionGlobal)
    {
        $this->farm = $farm;
        $this->milkProductionGlobal = $milkProductionGlobal;
    }

    public function delete()
    {
        $this->milkProductionGlobal->delete();
        return redirect()->route('admin.care-livestock.milk-production-global.index', $this->farm->id);
    }

    public function render()
    {
        return view('livewire.admin.milk-production-global.show-component');
    }
}