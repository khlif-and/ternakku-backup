<?php

namespace App\Services\Web\Qurban\Payment;

use Illuminate\Http\Request;
use App\Models\Farm;

class PaymentService
{
    protected PaymentCoreService $core;

    public function __construct(PaymentCoreService $core)
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

        return view('admin.qurban.payment.index', compact('farm'));
    }

    public function create()
    {
        $farm = $this->getFarm();

        return view('admin.qurban.payment.create', compact('farm'));
    }

    public function show($id)
    {
        $farm = $this->getFarm();
        $payment = $this->core->find($id);

        return view('admin.qurban.payment.show', compact('farm', 'payment'));
    }

    public function edit($id)
    {
        $farm = $this->getFarm();
        $payment = $this->core->find($id);

        return view('admin.qurban.payment.edit', compact('farm', 'payment'));
    }
}