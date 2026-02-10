<?php

namespace App\Services\Web\Farming\FeedMedicinePurchase;

use Illuminate\Http\Request;

class FeedMedicinePurchaseService
{
    protected FeedMedicinePurchaseCoreService $core;

    public function __construct(FeedMedicinePurchaseCoreService $core)
    {
        $this->core = $core;
    }

    public function index($farmId, Request $request)
    {
        $farm = request()->attributes->get('farm');

        return view('admin.care_livestock.feed_medicine_purchase.index', compact('farm'));
    }

    public function create($farmId)
    {
        $farm = request()->attributes->get('farm');
        return view('admin.care_livestock.feed_medicine_purchase.create', compact('farm'));
    }

    public function show($farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        $data = $this->core->findPurchase($farm, $id);

        return view('admin.care_livestock.feed_medicine_purchase.show', compact('farm', 'data'));
    }

    public function edit($farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        $data = $this->core->findPurchase($farm, $id);

        return view('admin.care_livestock.feed_medicine_purchase.edit', compact('farm', 'data'));
    }
}