<?php

namespace App\Http\Controllers\Admin\CareLivestock\UpdateClassification;

use App\Http\Controllers\Controller;
use App\Http\Requests\Farming\UpdateClassificationRequest;
use App\Models\Livestock;
use App\Models\LivestockClassification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LivestockClassificationController extends Controller
{
    /**
     * Menampilkan halaman daftar ternak beserta klasifikasinya.
     */
    public function index($farmId, Request $request): View
    {
        $farm = $request->attributes->get('farm');
        $livestocksQuery = $farm->livestocks();

        if ($request->filled('eartag_number')) {
            $livestocksQuery->where('eartag_number', 'like', '%' . $request->eartag_number . '%');
        }

        // DIUBAH: Menggunakan nama relasi yang benar 'livestockClassification' sesuai model Anda
        $livestocks = $livestocksQuery->with('livestockClassification')->paginate(15);

        return view('admin.care_livestock.classification.index', compact('farm', 'livestocks'));
    }

    /**
     * Menampilkan form untuk mengubah klasifikasi ternak.
     */
    public function edit($farmId, $id, Request $request): View
    {
        $farm = $request->attributes->get('farm');
        $livestock = $farm->livestocks()->findOrFail($id);
        $classifications = LivestockClassification::all();

        return view('admin.care_livestock.classification.edit', compact('farm', 'livestock', 'classifications'));
    }

    /**
     * Memperbarui klasifikasi ternak di database.
     */
    public function update(UpdateClassificationRequest $request, $farmId, $id): RedirectResponse
    {
        $farm = $request->attributes->get('farm');
        $livestock = $farm->livestocks()->findOrFail($id);
        $validated = $request->validated();

        $livestock->update($validated);

        return redirect()->route('admin.care-livestock.classification.index', $farmId)
            ->with('success', 'Klasifikasi ternak ' . $livestock->eartag_number . ' berhasil diperbarui.');
    }
}
