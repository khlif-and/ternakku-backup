<?php

namespace App\Http\Controllers\Admin\CareLivestock\ArtificialInseminasi;

use App\Http\Controllers\Controller;
use App\Models\InseminationArtificial;
use App\Services\Web\Farming\ArtificialInsemination\ArtificialInseminationService;
use Illuminate\Support\Facades\Log;

class ArtificialInseminasiController extends Controller
{
    public function __construct(
        private ArtificialInseminationService $inseminationService
    ) {}

    public function index($farmId)
    {
        $farm = request()->attributes->get('farm');
        return view('admin.care_livestock.artificial_inseminasi.index', compact('farm'));
    }

    public function create($farmId)
    {
        $farm = request()->attributes->get('farm');
        return view('admin.care_livestock.artificial_inseminasi.create', compact('farm'));
    }

    public function show($farmId, $id)
    {
        $farm = request()->attributes->get('farm');

        try {
            $item = InseminationArtificial::with([
                    'insemination',
                    'reproductionCycle.livestock.livestockType',
                    'reproductionCycle.livestock.livestockBreed',
                    'reproductionCycle.livestock.pen',
                ])
                ->whereHas('insemination', function ($q) use ($farm) {
                    $q->where('farm_id', $farm->id)->where('type', 'artificial');
                })
                ->findOrFail($id);

            return view('admin.care_livestock.artificial_inseminasi.show', compact('farm', 'item'));
        } catch (\Throwable $e) {
            // 🔹 Log detail error
            Log::error('❌ AI show error', [
                'message' => $e->getMessage(),
                'type' => get_class($e),
                'id' => $id,
                'farm_id' => $farm->id ?? null,
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->with('error', 'Terjadi kesalahan saat menampilkan data: ' . $e->getMessage());
        }
    }

    public function edit($farmId, $id)
    {
        $farm = request()->attributes->get('farm');
        return view('admin.care_livestock.artificial_inseminasi.edit', compact('farm', 'id'));
    }

    public function destroy($farmId, $id)
    {
        $farm = request()->attributes->get('farm');

        try {
            $item = InseminationArtificial::with(['insemination', 'reproductionCycle.livestock'])
                ->whereHas('insemination', function ($q) use ($farm) {
                    $q->where('farm_id', $farm->id)->where('type', 'artificial');
                })
                ->findOrFail($id);

            $this->inseminationService->deleteInsemination($item);

            return redirect()
                ->route('admin.care_livestock.artificial_inseminasi.index', ['farm_id' => $farm->id])
                ->with('success', 'Data berhasil dihapus.');
        } catch (\Throwable $e) {
            // 🔹 Log error lengkap
            Log::error('❌ AI destroy error', [
                'message' => $e->getMessage(),
                'type' => get_class($e),
                'ai_record_id' => $id,
                'farm_id' => $farm->id ?? null,
                'trace' => $e->getTraceAsString(),
            ]);

            // 🔹 Kirim pesan asli ke UI
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
