<?php

namespace App\Http\Controllers\Api\Qurban;

use App\Models\User;
use App\Models\UserBank;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\QurbanSavingRegistration;
use App\Models\QurbanSavingRegistrationUser;
use App\Http\Requests\Qurban\FindUserRequest;
use App\Http\Requests\Qurban\SavingRegisterRequest;
use App\Http\Resources\Qurban\SavingRegistrationListResource;
use App\Http\Resources\Qurban\SavingRegistrationDetailResource;

class SavingController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $userBankIds = UserBank::where('user_id' , $user->id)->pluck('id');
        $qurbanSavingRegistrationUserIds = QurbanSavingRegistrationUser::whereIn('user_bank_id' , $userBankIds)->pluck('id');

        $qurbanSavingRegistrations = QurbanSavingRegistration::whereIn('id', $qurbanSavingRegistrationUserIds)
            ->with(['livestockBreed', 'livestockBreed.livestockType', 'farm', 'province', 'regency', 'district', 'village'])
            ->get();

        // Mengembalikan data dalam bentuk resource collection
        $data = SavingRegistrationListResource::collection($qurbanSavingRegistrations);

        return ResponseHelper::success($data, 'Data retrieved successfully');
    }

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

                QurbanSavingRegistrationUser::create([
                    'user_bank_id' => $userBank->id,
                    'qurban_saving_registration_id' => $qurbanSavingRegistration->id,
                    'portion' => $user['portion']
                ]);
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
        $qurbanSavingRegistration = QurbanSavingRegistration::findOrFail($id);

        $data =  new SavingRegistrationDetailResource($qurbanSavingRegistration);

        return ResponseHelper::success($data, 'Data retrieved successfully');
    }

    public function findUser(FindUserRequest $request)
    {
        $validatedData = $request->validated();

        $user = User::verified()->where(function($query) use ($validatedData) {
            $query->where('email', $validatedData['username'])
                ->orWhere('phone_number', $validatedData['username']);
        })->firstOrFail();

        return ResponseHelper::success($user, 'Data retrieved successfully');
    }

}
