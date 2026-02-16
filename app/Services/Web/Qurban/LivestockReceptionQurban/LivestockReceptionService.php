<?php

namespace App\Services\Web\Qurban\LivestockReceptionQurban;

use Illuminate\Http\Request;
use App\Models\Farm;

class LivestockReceptionService
{
    protected LivestockReceptionCoreService $core;

    public function __construct(LivestockReceptionCoreService $core)
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

        return view('admin.qurban.livestock_reception.index', compact('farm'));
    }

    public function create()
    {
        $farm = $this->getFarm();
        $farm->load('pens');

        return view('admin.qurban.livestock_reception.create', compact('farm'));
    }

    public function show($id)
    {
        $farm = $this->getFarm();
        $reception = $this->core->find($farm, $id);

        return view('admin.qurban.livestock_reception.show', compact('farm', 'reception'));
    }

    public function edit($id)
    {
        $farm = $this->getFarm();
        $farm->load('pens');
        $reception = $this->core->find($farm, $id);

        return view('admin.qurban.livestock_reception.edit', compact('farm', 'reception'));
    }

    public function store(array $data)
    {
        $farm = $this->getFarm();

        try {
            $reception = $this->core->store($farm, $data);

            return redirect()->route('qurban.livestock-reception.show', $reception->id)
                ->with('success', 'Penerimaan ternak qurban berhasil ditambahkan.');
        } catch (\Throwable $e) {
            return redirect()->route('qurban.livestock-reception.create')
                ->with('error', 'Gagal menambahkan penerimaan: ' . $e->getMessage());
        }
    }

    public function update($id, array $data)
    {
        $farm = $this->getFarm();

        try {
            $this->core->update($farm, $id, $data);

            return redirect()->route('qurban.livestock-reception.show', $id)
                ->with('success', 'Penerimaan ternak qurban berhasil diperbarui.');
        } catch (\Throwable $e) {
            return redirect()->route('qurban.livestock-reception.edit', $id)
                ->with('error', 'Gagal memperbarui penerimaan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $farm = $this->getFarm();

        try {
            $this->core->delete($farm, $id);

            return redirect()->route('qurban.livestock-reception.index')
                ->with('success', 'Penerimaan ternak qurban berhasil dihapus.');
        } catch (\Throwable $e) {
            return redirect()->route('qurban.livestock-reception.index')
                ->with('error', 'Gagal menghapus penerimaan: ' . $e->getMessage());
        }
    }
}
