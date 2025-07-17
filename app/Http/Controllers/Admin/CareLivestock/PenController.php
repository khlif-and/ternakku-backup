<?php

namespace App\Http\Controllers\Admin\CareLivestock;

use App\Models\Pen;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Farming\PenStoreRequest;
use App\Http\Requests\Farming\PenUpdateRequest;
use App\Services\Farming\PenService;
use Illuminate\Support\Facades\Log;

class PenController extends Controller
{
    protected PenService $penService;

    public function __construct(PenService $penService)
    {
        $this->penService = $penService;
    }

    public function index($farmId)
    {
        $farm = request()->attributes->get('farm');
        $pens = $farm->pens()->latest('updated_at')->get();

        return view('admin.care_livestock.pens.index', compact('farm', 'pens'));
    }

    public function create($farmId)
    {
        $farm = request()->attributes->get('farm');

        return view('admin.care_livestock.pens.create', compact('farm'));
    }

    public function store(PenStoreRequest $request, $farmId)
    {
        $farm = request()->attributes->get('farm');

        try {
            $this->penService->create(
                $request->validated(),
                $farm,
                $request->file('photo')
            );

            return redirect()
                ->route('admin.care-livestock.pens.index', $farm->id)
                ->with('success', 'Data kandang berhasil ditambahkan.');
        } catch (\Throwable $e) {
            Log::error('Gagal menyimpan Pen', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan kandang. Silakan coba lagi.');
        }
    }

    public function edit($farmId, $penId)
    {
        $farm = request()->attributes->get('farm');
        $pen = $farm->pens()->findOrFail($penId);

        return view('admin.care_livestock.pens.edit', compact('farm', 'pen'));
    }

    public function update(PenUpdateRequest $request, $farmId, $penId)
    {
        $farm = request()->attributes->get('farm');
        $pen = $farm->pens()->findOrFail($penId);

        try {
            $this->penService->update(
                $pen,
                $request->validated(),
                $request->file('photo')
            );

            return redirect()
                ->route('admin.care-livestock.pens.index', $farm->id)
                ->with('success', 'Pen berhasil diperbarui.');
        } catch (\Throwable $e) {
            Log::error('Gagal update Pen', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui kandang.');
        }
    }

    public function destroy($farmId, $penId)
    {
        $farm = request()->attributes->get('farm');
        $pen = $farm->pens()->findOrFail($penId);

        try {
            $this->penService->delete($pen);

            return redirect()
                ->route('admin.care-livestock.pens.index', $farm->id)
                ->with('success', 'Pen berhasil dihapus.');
        } catch (\Throwable $e) {
            Log::error('Gagal hapus Pen', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat menghapus kandang.');
        }
    }
}
