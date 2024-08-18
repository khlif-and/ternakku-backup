<?php

namespace App\Http\Controllers\Api\Qurban;

use Illuminate\Http\Request;
use App\Models\QurbanContract;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Qurban\ContractStoreRequest;
use App\Http\Resources\Qurban\ContractDetailResource;

class ContractController extends Controller
{
    public function index()
    {

    }

    public function contract(ContractStoreRequest $request)
    {
        $contract = QurbanContract::create([
            'qurban_saving_registration_id' => $request->input('qurban_saving_registration_id'),
            'livestock_breed_id' => $request->input('livestock_breed_id'),
            'weight' => $request->input('weight'),
            'price_per_kg' => $request->input('price_per_kg'),
            'region_id' => $request->input('region_id'),
            'postal_code' => $request->input('postal_code'),
            'address_line' => $request->input('address_line'),
            'longitude' => $request->input('longitude'),
            'latitude' => $request->input('latitude'),
            'contract_date' => $request->input('contract_date'),
            'down_payment' => $request->input('down_payment'),
            'farm_id' => $request->input('farm_id'),
            'estimated_delivery_date' => $request->input('estimated_delivery_date'),
        ]);

        $data = new ContractDetailResource($contract);

        return ResponseHelper::success($data, 'Qurban contract created successfully');
    }

    public function detail($id)
    {
        $qurbanContract = QurbanContract::findOrFail($id);

        $data =  new ContractDetailResource($qurbanContract);

        return ResponseHelper::success($data, 'Data retrieved successfully');
    }
}
