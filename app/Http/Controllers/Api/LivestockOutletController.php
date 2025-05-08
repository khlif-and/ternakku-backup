<?php

namespace App\Http\Controllers\Api;

use App\Models\Farm;
use Illuminate\Http\Request;
use App\Enums\SubscriptionEnum;
use App\Helpers\ResponseHelper;
use App\Models\SubscriptionFarm;
use App\Http\Controllers\Controller;
use App\Http\Resources\FarmDetailResource;

class LivestockOutletController extends Controller
{
    public function farmIndex(Request $request)
    {
        $today = now()->format('Y-m-d');

        $subs = SubscriptionFarm::where('subscription_id', SubscriptionEnum::QURBAN_1446->value)
            ->where('end_date', '>=', $today)
            ->whereNotNull('confirmation_date')
            ->with(['farm'])
            ->get();

        $data = FarmDetailResource::collection($subs->pluck('farm')->shuffle());

        // Tentukan pesan respons
        $message = $subs->count() > 0 ? 'Farms retrieved successfully' : 'Data empty';

        // Kembalikan respons dengan data dan pesan
        return ResponseHelper::success($data, $message);
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
}
