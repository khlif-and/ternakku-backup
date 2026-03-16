<?php

namespace App\Http\Controllers\Admin\CareLivestock\FeedMedicinePurchase;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Web\Farming\FeedMedicinePurchase\FeedMedicinePurchaseService;
use App\Http\Requests\Farming\FeedMedicinePurchaseStoreRequest;
use App\Http\Requests\Farming\FeedMedicinePurchaseUpdateRequest;

class FeedMedicinePurchaseController extends Controller
{
    protected FeedMedicinePurchaseService $service;

    public function __construct(FeedMedicinePurchaseService $service)
    {
        $this->service = $service;
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
        $data = $this->service->find($farm, $id);

        return view('admin.care_livestock.feed_medicine_purchase.show', compact('farm', 'data'));
    }

    public function edit($farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        $data = $this->service->find($farm, $id);

        return view('admin.care_livestock.feed_medicine_purchase.edit', compact('farm', 'data'));
    }

    public function store(FeedMedicinePurchaseStoreRequest $request, $farmId)
    {
        $farm = request()->attributes->get('farm');

        try {
            $this->service->store($farm, $request->validated());

            return redirect()
                ->route('admin.care-livestock.feed-medicine-purchase.index', $farmId)
                ->with('success', 'Data berhasil ditambahkan.');
        } catch (\Throwable $e) {
            report($e);
            return back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    public function update(FeedMedicinePurchaseUpdateRequest $request, $farmId, $id)
    {
        $farm = request()->attributes->get('farm');

        try {
            $this->service->update($farm, $id, $request->validated());

            return redirect()
                ->route('admin.care-livestock.feed-medicine-purchase.show', [$farmId, $id])
                ->with('success', 'Data berhasil diperbarui.');
        } catch (\Throwable $e) {
            report($e);
            return back()->withInput()->with('error', 'Terjadi kesalahan saat memperbarui data.');
        }
    }

    public function destroy($farmId, $id)
    {
        $farm = request()->attributes->get('farm');

        try {
            $this->service->delete($farm, $id);

            return redirect()
                ->route('admin.care-livestock.feed-medicine-purchase.index', $farmId)
                ->with('success', 'Data berhasil dihapus.');
        } catch (\Throwable $e) {
            report($e);
            return back()->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }
}
