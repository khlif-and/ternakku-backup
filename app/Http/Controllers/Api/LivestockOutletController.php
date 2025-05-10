<?php

namespace App\Http\Controllers\Api;

use App\Models\Farm;
use App\Models\Livestock;
use Illuminate\Http\Request;
use App\Enums\SubscriptionEnum;
use App\Helpers\ResponseHelper;
use App\Models\SubscriptionFarm;
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
    
    
        // Ambil semua farm_id dari subscription yang valid
        $farmIds = SubscriptionFarm::where('subscription_id', SubscriptionEnum::QURBAN_1446->value)
            ->where('end_date', '>=', $today)
            ->whereNotNull('confirmation_date')
            ->pluck('farm_id');
    
        $livestockQuery = Livestock::whereIn('farm_id', $farmIds)
            ->with(['farm.farmDetail.region']); // Eager load region melalui farmDetail
    
        // Filter berdasarkan farm_id (jika ada)
        if ($filterFarmId && $farmIds->contains($filterFarmId)) {
            $livestockQuery->where('farm_id', $filterFarmId);
        }
    
        // Filter berdasarkan search parameter
        if ($search) {
            $livestockQuery->where(function ($query) use ($search) {
                $query->where('eartag_number', 'like', "%$search%")
                    ->orWhereHas('farm.farmDetail', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%")
                          ->orWhereHas('region', function ($q) use ($search) {
                              $q->where('name', 'like', "%$search%"); // Pencarian berdasarkan region.name
                          });
                    });
            });
        }

        // Filter berdasarkan livestock_sex_id
        if ($filterSexId) {
            $livestockQuery->where('livestock_sex_id', $filterSexId);
        }

        // Filter berdasarkan livestock_breed_id
        if ($filterBreedId) {
            $livestockQuery->where('livestock_breed_id', $filterBreedId);
        }

        // Filter berdasarkan livestock_type_id
        if ($filterTypeId) {
            $livestockQuery->where('livestock_type_id', $filterTypeId);
        }

        // Filter berdasarkan min_weight
        if ($minWeight) {
            $livestockQuery->where('last_weight', '>=', $minWeight);
        }

        // Filter berdasarkan max_weight
        if ($maxWeight) {
            $livestockQuery->where('last_weight', '<=', $maxWeight);
        }

    
        // Ambil data dan shuffle
        $livestocks = $livestockQuery->get()->shuffle();
    
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
