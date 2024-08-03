<?php

namespace App\Http\Controllers\Api\Qurban;

use App\Models\UserBank;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\QurbanSavingRegistration;
use App\Http\Requests\Qurban\SavingRegisterRequest;
use App\Http\Resources\Qurban\SavingRegistrationDetailResource;

class SavingController extends Controller
{
    public function register(SavingRegisterRequest $request)
    {
        try {
            DB::beginTransaction();

            // Create new Qurban saving registration
            $qurbanSavingRegistration = QurbanSavingRegistration::create($request->only([
                'livestock_breed_id',
                'farm_id',
                'weight',
                'price_per_kg',
                'province_id',
                'regency_id',
                'district_id',
                'village_id',
                'postal_code',
                'address_line',
                'duration_months'
            ]));

            // Save user_bank information
            foreach ($request->users as $user) {
                $userBank = UserBank::firstOrCreate([
                    'user_id' => $user['user_id'],
                    'bank_id' => $user['bank_id'],
                    'account_number' => $user['account_number']
                ]);

                // Associate user_bank with Qurban saving registration
                $qurbanSavingRegistration->users()->attach($userBank->id, ['portion' => $user['portion']]);
            }

            DB::commit();

            $data = new SavingRegistrationDetailResource($qurbanSavingRegistration);

            return ResponseHelper::success($data, 'Qurban saving registration created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return ResponseHelper::error('Failed to create Qurban saving registration: ' . $e->getMessage(), 500);
        }
    }

    public function detail($id)
    {
        $qurbanSavingRegistration = QurbanSavingRegistration::with('users')->findOrFail($id);

        $data =  new SavingRegistrationDetailResource($qurbanSavingRegistration);

        return ResponseHelper::success($data, 'Qurban saving registration created successfully');
        ;
    }

}
