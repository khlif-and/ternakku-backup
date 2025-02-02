<?php

namespace App\Services;

use App\Models\Farm;
use App\Models\FarmUser;

class FarmService
{
    public function getUsers($farmId)
    {
        $farm = Farm::findOrFail($farmId);

        if(!$farm){
            return [
                'error' => true,
                'message' => "Farm not found",
                'http_code' => 404,
                'data' => null
            ];
        }

        if($farm->owner_id !== auth()->id()){
            return [
                'error' => true,
                'message' => "You don't have permission to access this",
                'http_code' => 403,
                'data' => null
            ];
        }

        $famUsers = FarmUser::with(['user' , 'farm'])->where('farm_id', $farmId)->get();

        return [
            'error' => false,
            'message' => "Success",
            'http_code' => 200,
            'data' => $famUsers
        ];
    }
}
