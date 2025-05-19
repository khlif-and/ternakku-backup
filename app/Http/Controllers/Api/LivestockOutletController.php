<?php

namespace App\Http\Controllers\Api;

use App\Models\Farm;
use App\Models\Livestock;
use Illuminate\Http\Request;
use App\Enums\SubscriptionEnum;
use App\Helpers\ResponseHelper;
use App\Models\SubscriptionFarm;
use App\Enums\LivestockStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\LivestockResource;
use App\Http\Resources\FarmDetailResource;
use Illuminate\Pagination\LengthAwarePaginator;

class LivestockOutletController extends Controller
{

    public function farmIndex(Request $request)
    {
        $today = now()->format('Y-m-d');
        $search = $request->get('search');
        $perPage = $request->get('per_page', 10);
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
    
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
            ->with([
                'farm',
                'farm.farmDetail.region',
            ])
            ->get();
    
        $shuffledFarms = $subs->pluck('farm')->shuffle();
    
        $currentItems = $shuffledFarms->slice(($currentPage - 1) * $perPage, $perPage)->values();
    
        $paginated = new LengthAwarePaginator(
            FarmDetailResource::collection($currentItems),
            $shuffledFarms->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );
    
        $message = $subs->count() > 0 ? 'Farms retrieved successfully' : 'Data empty';
    
        return ResponseHelper::success($paginated, $message);
    }
    
    public function farmDetail($id)
    {
        $farm = Farm::find($id);
        
        if (!$farm) {
            return ResponseHelper::error('Farm not found', 404);
        }
        // Jika farm ditemukan, gunakan FarmDetailResource untuk mengambil detailnya
        $data = new FarmDetailResource($farm);

        return ResponseHelper::success($data, 'Farm detail retrieved successfully');
    }

    public function livestockIndex(Request $request)
    {
        $today = now()->format('Y-m-d');
        $perPage = $request->get('per_page', 10);
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $search = $request->get('search');
        $filterFarmId = $request->get('farm_id');
        $filterSexId = $request->get('livestock_sex_id');
        $filterBreedId = $request->get('livestock_breed_id');
        $filterTypeId = $request->get('livestock_type_id');
        $minWeight = $request->get('min_weight');
        $maxWeight = $request->get('max_weight');
        $minPrice = $request->get('min_price');
        $maxPrice = $request->get('max_price');

    
        // Ambil semua farm_id dari subscription yang valid
        $farmIds = SubscriptionFarm::where('subscription_id', SubscriptionEnum::QURBAN_1446->value)
            ->where('end_date', '>=', $today)
            ->whereNotNull('confirmation_date')
            ->pluck('farm_id');
    
        $livestockQuery = Livestock::whereIn('farm_id', $farmIds)
            ->where('livestock_status_id', LivestockStatusEnum::HIDUP->value)
            ->with(['farm.farmDetail.region']);
    
        if ($filterFarmId && $farmIds->contains($filterFarmId)) {
            $livestockQuery->where('farm_id', $filterFarmId);
        }
    
        if ($search) {
            $livestockQuery->where(function ($query) use ($search) {
                $query->where('eartag_number', 'like', "%$search%")
                    ->orWhereHas('farm.farmDetail', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%")
                            ->orWhereHas('region', function ($q) use ($search) {
                                $q->where('name', 'like', "%$search%");
                            });
                    });
            });
        }
    
        if ($filterSexId) {
            $livestockQuery->where('livestock_sex_id', $filterSexId);
        }
    
        if ($filterBreedId) {
            $livestockQuery->where('livestock_breed_id', $filterBreedId);
        }
    
        if ($filterTypeId) {
            $livestockQuery->where('livestock_type_id', $filterTypeId);
        }
    
        if ($minWeight) {
            $livestockQuery->where('last_weight', '>=', $minWeight);
        }
    
        if ($maxWeight) {
            $livestockQuery->where('last_weight', '<=', $maxWeight);
        }
    
        // Ambil data
        $livestocks = $livestockQuery->get();
    
        // Hitung dan filter berdasarkan qurban_price
        $livestocks = $livestocks->filter(function ($item) use ($minPrice, $maxPrice) {
            $price = getEstimationQurbanPrice($item->farm_id, $item->livestock_type_id, $item->last_weight, 1446);
            if ($minPrice !== null && $price < $minPrice) {
                return false;
            }
            if ($maxPrice !== null && $price > $maxPrice) {
                return false;
            }
            // Simpan price ke property sementara (untuk sorting nanti)
            $item->calculated_qurban_price = $price;
            return true;
        })->values();
    
        // Sorting
        $sortBy = $request->get('sort_by');
        $sortOrder = strtolower($request->get('sort_order', 'asc'));
    
        if ($sortBy === 'weight') {
            $livestocks = $livestocks->sortBy('last_weight', SORT_REGULAR, $sortOrder === 'desc')->values();
        } elseif ($sortBy === 'price') {
            $livestocks = $livestocks->sortBy('calculated_qurban_price', SORT_REGULAR, $sortOrder === 'desc')->values();
        } else {
            $livestocks = $livestocks->shuffle();
        }
    
        // Paginasi manual
        $currentItems = $livestocks->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $paginated = new LengthAwarePaginator(
            LivestockResource::collection($currentItems),
            $livestocks->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );
    
        $message = $livestocks->count() > 0 ? 'Livestocks retrieved successfully' : 'Data empty';
    
        return ResponseHelper::success($paginated, $message);
    }
    
    
    public function livestockDetail($id)
    {
        $livestock = Livestock::find($id);
        
        if (!$livestock) {
            return ResponseHelper::error('livestock not found', 404);
        }
        // Jika livestock ditemukan, gunakan livestockDetailResource untuk mengambil detailnya
        $data = new LivestockResource($livestock);

        return ResponseHelper::success($data, 'livestock detail retrieved successfully');
    }
}
