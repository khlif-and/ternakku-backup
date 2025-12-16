<?php

// DIUBAH: Namespace disesuaikan dengan lokasi file Anda
namespace App\Http\Controllers\Admin\LivestockOutlet;

use App\Models\Farm;
use App\Models\Livestock;
use App\Models\LivestockBreed;
use App\Models\LivestockSex;
use App\Models\LivestockType;
use Illuminate\Http\Request;
use App\Enums\SubscriptionEnum;
use App\Enums\LivestockStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\SubscriptionFarm;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB; // <-- TAMBAHKAN IMPORT INI

class LivestockOutletController extends Controller
{
    /**
     * BARU: Menampilkan halaman dashboard untuk outlet ternak.
     * Method ini akan mengambil data ringkasan seperti jumlah outlet aktif dan total ternak yang tersedia.
     */
    public function dashboard(): View
    {
        $today = now()->format('Y-m-d');

        // Mengambil ID semua peternakan yang memiliki subscription Qurban 1446 H yang aktif
        $farmIds = SubscriptionFarm::where('subscription_id', SubscriptionEnum::QURBAN_1446->value)
            ->where('end_date', '>=', $today)
            ->whereNotNull('confirmation_date')
            ->pluck('farm_id');

        // Menghitung jumlah peternakan (outlet) yang aktif
        $totalFarms = $farmIds->count();

        // Menghitung jumlah ternak yang tersedia dari semua outlet aktif
        $totalLivestocks = Livestock::whereIn('farm_id', $farmIds)
            ->where('livestock_status_id', LivestockStatusEnum::HIDUP->value)
            ->count();

        // [FIX] Mengambil data untuk grafik berdasarkan tipe ternak
        $livestockByType = Livestock::whereIn('farm_id', $farmIds)
            ->where('livestock_status_id', LivestockStatusEnum::HIDUP->value)
            ->join('livestock_types', 'livestocks.livestock_type_id', '=', 'livestock_types.id')
            ->select('livestock_types.name', DB::raw('count(livestocks.id) as total'))
            ->groupBy('livestock_types.name')
            ->get();

        // [FIX] Mengirim data baru ($livestockByType) ke view
        return view('admin.livestock_outlet.dashboard', compact('totalFarms', 'totalLivestocks', 'livestockByType'));
    }

    /**
     * Menampilkan halaman daftar peternakan (outlet) yang berpartisipasi.
     */
    public function indexFarms(Request $request): View
    {
        $today = now()->format('Y-m-d');
        $search = $request->get('search');
        $perPage = $request->get('per_page', 12);
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        // Mengambil data subscription farm yang aktif
        $subs = SubscriptionFarm::where('subscription_id', SubscriptionEnum::QURBAN_1446->value)
            ->where('end_date', '>=', $today)
            ->whereNotNull('confirmation_date')
            ->when($search, function ($query) use ($search) {
                $query->whereHas('farm', function ($farmQuery) use ($search) {
                    $farmQuery->where('name', 'like', '%' . $search . '%')
                        ->orWhereHas('farmDetail.region', function ($q) use ($search) {
                            $q->where('name', 'like', '%' . $search . '%');
                        });
                });
            })
            ->with(['farm', 'farm.farmDetail.region'])
            ->get();

        // Logika shuffle dipertahankan sesuai API asli
        $shuffledFarms = $subs->pluck('farm')->shuffle();

        // Paginasi manual
        $currentItems = $shuffledFarms->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $paginatedFarms = new LengthAwarePaginator(
            $currentItems,
            $shuffledFarms->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // DIUBAH: Path view disesuaikan ke folder 'admin'
        return view('admin.livestock_outlet.farms.index', compact('paginatedFarms', 'search'));
    }

    /**
     * Menampilkan halaman detail peternakan.
     */
    public function showFarm($id): View
    {
        $farm = Farm::with('farmDetail.region')->findOrFail($id);

        // DIUBAH: Path view disesuaikan ke folder 'admin'
        return view('admin.livestock_outlet.farms.show', compact('farm'));
    }

    /**
     * Menampilkan halaman daftar ternak dari semua outlet.
     */
    public function indexLivestocks(Request $request): View
    {
        $today = now()->format('Y-m-d');
        $perPage = $request->get('per_page', 12);
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        // Mengambil semua nilai filter dari request
        $filters = [
            'search' => $request->get('search'),
            'farm_id' => $request->get('farm_id'),
            'livestock_sex_id' => $request->get('livestock_sex_id'),
            'livestock_breed_id' => $request->get('livestock_breed_id'),
            'livestock_type_id' => $request->get('livestock_type_id'),
            'min_weight' => $request->get('min_weight'),
            'max_weight' => $request->get('max_weight'),
            'min_price' => $request->get('min_price'),
            'max_price' => $request->get('max_price'),
            'sort_by' => $request->get('sort_by'),
            'sort_order' => strtolower($request->get('sort_order', 'asc')),
        ];

        // Ambil semua farm_id dari subscription yang valid
        $farmIds = SubscriptionFarm::where('subscription_id', SubscriptionEnum::QURBAN_1446->value)
            ->where('end_date', '>=', $today)
            ->whereNotNull('confirmation_date')
            ->pluck('farm_id');

        $livestockQuery = Livestock::whereIn('farm_id', $farmIds)
            ->where('livestock_status_id', LivestockStatusEnum::HIDUP->value)
            ->with(['farm.farmDetail.region']);

        // Terapkan filter-filter
        if ($filters['farm_id'] && $farmIds->contains($filters['farm_id'])) {
            $livestockQuery->where('farm_id', $filters['farm_id']);
        }
        if ($filters['search']) {
            $livestockQuery->where('eartag_number', 'like', "%{$filters['search']}%");
        }
        if ($filters['livestock_sex_id']) {
            $livestockQuery->where('livestock_sex_id', $filters['livestock_sex_id']);
        }
        if ($filters['livestock_breed_id']) {
            $livestockQuery->where('livestock_breed_id', $filters['livestock_breed_id']);
        }
        if ($filters['livestock_type_id']) {
            $livestockQuery->where('livestock_type_id', $filters['livestock_type_id']);
        }
        if ($filters['min_weight']) {
            $livestockQuery->where('last_weight', '>=', $filters['min_weight']);
        }
        if ($filters['max_weight']) {
            $livestockQuery->where('last_weight', '<=', $filters['max_weight']);
        }

        $livestocks = $livestockQuery->get();

        // Filter berdasarkan harga (logika ini tetap di PHP karena memanggil helper)
        $livestocks = $livestocks->filter(function ($item) use ($filters) {
            // ASUMSI: helper 'getEstimationQurbanPrice' ada
            $price = getEstimationQurbanPrice($item->farm_id, $item->livestock_type_id, $item->last_weight, 1446);
            if ($filters['min_price'] !== null && $price < $filters['min_price']) return false;
            if ($filters['max_price'] !== null && $price > $filters['max_price']) return false;

            $item->calculated_qurban_price = $price;
            return true;
        })->values();

        // Sorting
        if ($filters['sort_by'] === 'weight') {
            $livestocks = $livestocks->sortBy('last_weight', SORT_REGULAR, $filters['sort_order'] === 'desc')->values();
        } elseif ($filters['sort_by'] === 'price') {
            $livestocks = $livestocks->sortBy('calculated_qurban_price', SORT_REGULAR, $filters['sort_order'] === 'desc')->values();
        } else {
            $livestocks = $livestocks->shuffle();
        }

        // Paginasi manual
        $currentItems = $livestocks->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $paginatedLivestocks = new LengthAwarePaginator(
            $currentItems,
            $livestocks->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // Data untuk dropdown filter
        $farms = Farm::whereIn('id', $farmIds)->get();
        $breeds = LivestockBreed::all();
        $types = LivestockType::all();
        $sexes = LivestockSex::all();

        // DIUBAH: Path view disesuaikan ke folder 'admin'
        return view('admin.livestock_outlet.livestocks.index', compact(
            'paginatedLivestocks',
            'farms',
            'breeds',
            'types',
            'sexes',
            'filters'
        ));
    }

    /**
     * Menampilkan halaman detail ternak.
     */
    public function showLivestock($id): View
    {
        $livestock = Livestock::with('farm.farmDetail.region')->findOrFail($id);

        // Hitung estimasi harga untuk ditampilkan di halaman detail
        // ASUMSI: helper 'getEstimationQurbanPrice' ada
        $qurbanPrice = getEstimationQurbanPrice($livestock->farm_id, $livestock->livestock_type_id, $livestock->last_weight, 1446);

        // DIUBAH: Path view disesuaikan ke folder 'admin'
        return view('admin.livestock_outlet.livestocks.show', compact('livestock', 'qurbanPrice'));
    }
}

