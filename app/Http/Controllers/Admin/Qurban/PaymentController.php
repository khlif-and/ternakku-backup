<?php

namespace App\Http\Controllers\Admin\Qurban;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Web\Qurban\Payment\PaymentService;

class PaymentController extends Controller
{
    protected PaymentService $service;

    public function __construct(PaymentService $service)
    {
        $this->service = $service;
    }

    private function getFarm()
    {
        $farm = request()->attributes->get('farm');

        if (!$farm && session()->has('selected_farm')) {
            $farm = \App\Models\Farm::find(session('selected_farm'));
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
        $payment = $this->service->find($id);

        return view('admin.qurban.payment.show', compact('farm', 'payment'));
    }

    public function edit($id)
    {
        $farm = $this->getFarm();
        $payment = $this->service->find($id);

        return view('admin.qurban.payment.edit', compact('farm', 'payment'));
    }
}