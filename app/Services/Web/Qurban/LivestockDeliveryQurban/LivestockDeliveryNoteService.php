<?php

namespace App\Services\Web\Qurban\LivestockDeliveryQurban;

use Illuminate\Http\Request;
use App\Models\Farm;

class LivestockDeliveryNoteService
{
    protected LivestockDeliveryNoteCoreService $core;

    public function __construct(LivestockDeliveryNoteCoreService $core)
    {
        $this->core = $core;
    }

    private function getFarm()
    {
        $farm = request()->attributes->get('farm');

        if (!$farm && session()->has('selected_farm')) {
            $farm = Farm::find(session('selected_farm'));
        }

        return $farm;
    }

    public function index(Request $request)
    {
        $farm = $this->getFarm();

        return view('admin.qurban.livestock_delivery_note_qurban.index', compact('farm'));
    }

    public function create()
    {
        $farm = $this->getFarm();

        return view('admin.qurban.livestock_delivery_note_qurban.create', compact('farm'));
    }

    public function show($id)
    {
        $farm = $this->getFarm();
        $delivery = $this->core->find($id);

        return view('admin.qurban.livestock_delivery_note_qurban.show', compact('farm', 'delivery'));
    }

    public function edit($id)
    {
        $farm = $this->getFarm();
        $delivery = $this->core->find($id);

        return view('admin.qurban.livestock_delivery_note_qurban.edit', compact('farm', 'delivery'));
    }
}