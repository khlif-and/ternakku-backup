<?php

namespace App\Services\Web\Qurban\QurbanDelivery;

use Illuminate\Http\Request;
use App\Models\Farm;

class QurbanDeliveryService
{
    protected QurbanDeliveryCoreService $core;

    public function __construct(QurbanDeliveryCoreService $core)
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

        return view('admin.qurban.qurban_delivery.index', compact('farm'));
    }

    public function create()
    {
        $farm = $this->getFarm();

        return view('admin.qurban.qurban_delivery.create', compact('farm'));
    }

    public function show($id)
    {
        $farm = $this->getFarm();
        $delivery = $this->core->find($id);

        return view('admin.qurban.qurban_delivery.show', compact('farm', 'delivery'));
    }

    public function edit($id)
    {
        $farm = $this->getFarm();
        $delivery = $this->core->find($id);

        return view('admin.qurban.qurban_delivery.edit', compact('farm', 'delivery'));
    }
}