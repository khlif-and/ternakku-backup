<?php

namespace App\Livewire\Qurban;

use Livewire\Component;

class SidebarComponent extends Component
{
    public $farm;
    public bool $sidebarCollapsed = false;
    public bool $dataAwalOpen = false;
    public bool $aktivitasOpen = false;
    public bool $laporanOpen = false;

    public function mount($farm = null)
    {
        $this->farm = $farm;
        $this->initMenuStates();
    }

    protected function initMenuStates(): void
    {
        $currentPath = request()->path();

        $this->dataAwalOpen = str_contains($currentPath, 'qurban/farm') 
            || str_contains($currentPath, 'qurban/customer')
            || str_contains($currentPath, 'qurban/fleet')
            || str_contains($currentPath, 'qurban/driver');

        $this->aktivitasOpen = str_contains($currentPath, 'qurban/sales-order')
            || str_contains($currentPath, 'qurban/sales-livestock')
            || str_contains($currentPath, 'qurban/reweight')
            || str_contains($currentPath, 'qurban/payment')
            || str_contains($currentPath, 'qurban/delivery')
            || str_contains($currentPath, 'qurban/fleet-tracking')
            || str_contains($currentPath, 'qurban/livestock-delivery-note')
            || str_contains($currentPath, 'qurban/cancelation');

        $this->laporanOpen = str_contains($currentPath, 'qurban/report')
            || str_contains($currentPath, 'qurban/population-report');
    }

    public function toggleSidebar(): void
    {
        $this->sidebarCollapsed = !$this->sidebarCollapsed;
    }

    public function toggleDataAwal(): void
    {
        $this->dataAwalOpen = !$this->dataAwalOpen;
    }

    public function toggleAktivitas(): void
    {
        $this->aktivitasOpen = !$this->aktivitasOpen;
    }

    public function toggleLaporan(): void
    {
        $this->laporanOpen = !$this->laporanOpen;
    }

    public function render()
    {
        return view('livewire.qurban.sidebar-component');
    }
}
