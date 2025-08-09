<?php

namespace App\Http\Controllers\Admin\CareLivestock\FeedingLivestock;

use App\Http\Controllers\Controller;
use App\Models\FeedingH;
use App\Models\FeedingColonyD;
use App\Models\FeedingColonyItem;
use App\Models\FeedingColonyLivestock;
use App\Models\LivestockExpense;
use App\Enums\LivestockExpenseTypeEnum;
use App\Http\Requests\Farming\FeedingColonyStoreRequest;
use App\Http\Requests\Farming\FeedingColonyUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FeedingColonyController extends Controller
{
    // LIST
    public function index($farmId, Request $request)
    {
        $farm = request()->attributes->get('farm');

        $query = FeedingColonyD::with(['feedingH', 'pen'])
            ->withCount('feedingColonyItems')
            ->whereHas('feedingH', function ($q) use ($farm, $request) {
                $q->where('farm_id', $farm->id)->where('type', 'colony');

                if ($request->filled('start_date')) {
                    $q->where('transaction_date', '>=', $request->input('start_date'));
                }
                if ($request->filled('end_date')) {
                    $q->where('transaction_date', '<=', $request->input('end_date'));
                }
            });

        // Filter berdasarkan kandang (pen) — opsional
        if ($request->filled('pen_id')) {
            $query->where('pen_id', $request->integer('pen_id'));
        }

        $items = $query->get();

        return view('admin.care_livestock.feeding_colony.index', [
            'farm'    => $farm,
            'items'   => $items,
            'filters' => $request->only(['start_date', 'end_date', 'pen_id']),
        ]);
    }

    // FORM CREATE (kirim daftar pens ke Blade)
    public function create($farmId, Request $request)
    {
        $farm = request()->attributes->get('farm');

        $pens = $farm->pens()->get();
        $fromPen = null;

        // Jika datang dari halaman kandang tertentu (?pen_id=...), set default selected
        if ($request->filled('pen_id')) {
            $fromPen = $farm->pens()->find($request->integer('pen_id'));
        }

        return view('admin.care_livestock.feeding_colony.create', compact('farm', 'pens', 'fromPen'));
    }

    // STORE (anggota koloni = isi kandang pada saat transaksi)
    public function store(FeedingColonyStoreRequest $request, $farmId)
    {
        $validated = $request->validated();
        $farm = request()->attributes->get('farm');

        $pen = $farm->pens()->find($validated['pen_id'] ?? null);
        if (!$pen) {
            return back()->withInput()->with('error', 'Pen not found.');
        }

        $livestocks = $pen->livestocks; // anggota koloni saat ini = isi kandang
        $totalLivestocks = $livestocks->count();

        if ($totalLivestocks < 1) {
            return back()->withInput()->with('error', 'There is no livestock in this pen.');
        }

        try {
            DB::beginTransaction();

            $feedingH = FeedingH::create([
                'farm_id'          => $farm->id,
                'transaction_date' => $validated['transaction_date'],
                'type'             => 'colony',
                'notes'            => $validated['notes'] ?? null,
            ]);

            $feedingColonyD = FeedingColonyD::create([
                'feeding_h_id'    => $feedingH->id,
                'pen_id'          => (int) $pen->id,
                'notes'           => $validated['notes'] ?? null,
                'total_livestock' => $totalLivestocks,
                'total_cost'      => 0,
                'average_cost'    => 0,
            ]);

            $totalCost = 0;

            foreach ($validated['items'] as $item) {
                $totalPrice = $item['qty_kg'] * $item['price_per_kg'];
                $totalCost += $totalPrice;

                FeedingColonyItem::create([
                    'feeding_colony_d_id' => $feedingColonyD->id,
                    'type'                => $item['type'],
                    'name'                => $item['name'],
                    'qty_kg'              => $item['qty_kg'],
                    'price_per_kg'        => $item['price_per_kg'],
                    'total_price'         => $totalPrice,
                ]);
            }

            $averageCost = $totalLivestocks > 0 ? ($totalCost / $totalLivestocks) : 0;

            $feedingColonyD->update([
                'total_cost'   => $totalCost,
                'average_cost' => $averageCost,
            ]);

            // Simpan anggota koloni (pivot) & catat biaya per ternak
            foreach ($livestocks as $livestock) {
                FeedingColonyLivestock::create([
                    'feeding_colony_d_id' => $feedingColonyD->id,
                    'livestock_id'        => $livestock->id,
                ]);

                $expense = LivestockExpense::firstOrCreate(
                    [
                        'livestock_id'              => $livestock->id,
                        'livestock_expense_type_id' => LivestockExpenseTypeEnum::FEEDING->value,
                    ],
                    ['amount' => 0]
                );

                $expense->update(['amount' => $expense->amount + $averageCost]);
            }

            DB::commit();

            return redirect()
                ->route('admin.care-livestock.feeding-colony.show', ['farm_id' => $farmId, 'id' => $feedingColonyD->id])
                ->with('success', 'Data created successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Create FeedingColony Error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'An error occurred while recording the data.');
        }
    }

    // SHOW
    public function show($farmId, $feedingColonyId)
    {
        $farm = request()->attributes->get('farm');

        $feedingColony = FeedingColonyD::with(['feedingH', 'pen', 'livestocks', 'feedingColonyItems'])
            ->whereHas('feedingH', fn($q) => $q->where('farm_id', $farm->id)->where('type', 'colony'))
            ->findOrFail($feedingColonyId);

        return view('admin.care_livestock.feeding_colony.show', compact('farm', 'feedingColony'));
    }

    // FORM EDIT (opsional: kirim pens jika form edit butuh dropdown kandang)
    public function edit($farmId, $feedingColonyId)
    {
        $farm = request()->attributes->get('farm');

        $feedingColony = FeedingColonyD::with(['feedingH', 'pen', 'livestocks', 'feedingColonyItems'])
            ->whereHas('feedingH', fn($q) => $q->where('farm_id', $farm->id)->where('type', 'colony'))
            ->findOrFail($feedingColonyId);

        // Jika edit view ingin menampilkan pilihan kandang, kirim daftar pens
        $pens = $farm->pens()->get();

        return view('admin.care_livestock.feeding_colony.edit', compact('farm', 'feedingColony', 'pens'));
    }

    // UPDATE (tidak mengubah anggota koloni/pen; hanya tanggal/notes/items & re-hitungan biaya)
    public function update(FeedingColonyUpdateRequest $request, $farmId, $feedingColonyId)
    {
        $validated = $request->validated();
        $farm = request()->attributes->get('farm');

        $feedingColonyD = FeedingColonyD::with(['livestocks', 'feedingH'])
            ->whereHas('feedingH', fn($q) => $q->where('farm_id', $farm->id)->where('type', 'colony'))
            ->findOrFail($feedingColonyId);

        try {
            DB::beginTransaction();

            // 1) Update header (tanggal/notes)
            $feedingH = $feedingColonyD->feedingH;
            $feedingH->update([
                'transaction_date' => $validated['transaction_date'],
                'notes'            => $validated['notes'] ?? null,
            ]);

            // 2) Rollback biaya lama dari anggota koloni yang tercatat (pivot)
            foreach ($feedingColonyD->livestocks as $livestock) {
                $expense = LivestockExpense::where('livestock_id', $livestock->id)
                    ->where('livestock_expense_type_id', LivestockExpenseTypeEnum::FEEDING->value)
                    ->first();

                if ($expense) {
                    $expense->update([
                        'amount' => $expense->amount - ($feedingColonyD->average_cost ?? 0),
                    ]);
                }
            }

            // 3) Hapus item lama & reset total
            FeedingColonyItem::where('feeding_colony_d_id', $feedingColonyD->id)->delete();

            $feedingColonyD->update([
                'notes'         => $validated['notes'] ?? null,
                'total_cost'    => 0,
                'average_cost'  => 0,
            ]);

            // 4) Tambahkan item baru & hitung ulang total
            $totalCost = 0;
            foreach ($validated['items'] as $item) {
                $totalPrice = $item['qty_kg'] * $item['price_per_kg'];
                $totalCost += $totalPrice;

                FeedingColonyItem::create([
                    'feeding_colony_d_id' => $feedingColonyD->id,
                    'type'                => $item['type'],
                    'name'                => $item['name'],
                    'qty_kg'              => $item['qty_kg'],
                    'price_per_kg'        => $item['price_per_kg'],
                    'total_price'         => $totalPrice,
                ]);
            }

            $totalLivestocks = $feedingColonyD->livestocks->count(); // anggota koloni tetap (pivot)
            $averageCost = $totalLivestocks > 0 ? ($totalCost / $totalLivestocks) : 0;

            $feedingColonyD->update([
                'total_cost'      => $totalCost,
                'average_cost'    => $averageCost,
                'total_livestock' => $totalLivestocks,
            ]);

            // 5) Tambahkan biaya baru ke tiap anggota koloni
            foreach ($feedingColonyD->livestocks as $livestock) {
                $expense = LivestockExpense::firstOrCreate(
                    [
                        'livestock_id'              => $livestock->id,
                        'livestock_expense_type_id' => LivestockExpenseTypeEnum::FEEDING->value,
                    ],
                    ['amount' => 0]
                );

                $expense->update([
                    'amount' => $expense->amount + $averageCost,
                ]);
            }

            DB::commit();

            return redirect()
                ->route('admin.care-livestock.feeding-colony.show', ['farm_id' => $farmId, 'id' => $feedingColonyD->id])
                ->with('success', 'Data updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Update FeedingColony Error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'An error occurred while updating the data.');
        }
    }

    // DELETE
    public function destroy($farmId, $feedingColonyId)
    {
        $farm = request()->attributes->get('farm');

        $feedingColonyD = FeedingColonyD::with(['livestocks', 'feedingH'])
            ->whereHas('feedingH', fn($q) => $q->where('farm_id', $farm->id)->where('type', 'colony'))
            ->findOrFail($feedingColonyId);

        try {
            DB::beginTransaction();

            // Kurangi biaya di tiap anggota koloni (pivot)
            foreach ($feedingColonyD->livestocks as $livestock) {
                $expense = LivestockExpense::where('livestock_id', $livestock->id)
                    ->where('livestock_expense_type_id', LivestockExpenseTypeEnum::FEEDING->value)
                    ->first();

                if ($expense) {
                    $expense->update([
                        'amount' => $expense->amount - ($feedingColonyD->average_cost ?? 0),
                    ]);
                }
            }

            // Hapus detail & pivot
            FeedingColonyItem::where('feeding_colony_d_id', $feedingColonyD->id)->delete();
            FeedingColonyLivestock::where('feeding_colony_d_id', $feedingColonyD->id)->delete();

            // Hapus D
            $feedingColonyD->delete();

            // Hapus H jika tak ada D lain
            $feedingH = $feedingColonyD->feedingH;
            if ($feedingH && !$feedingH->feedingColonyD()->exists()) {
                $feedingH->delete();
            }

            DB::commit();

            return redirect()
                ->route('admin.care-livestock.feeding-colony.index', ['farm_id' => $farmId])
                ->with('success', 'Data deleted successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Delete FeedingColony Error: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while deleting the data.');
        }
    }
}
