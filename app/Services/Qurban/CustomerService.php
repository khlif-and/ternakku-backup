<?php

namespace App\Services\Qurban;

use App\Models\User;
use App\Enums\RoleEnum;
use App\Models\FarmUser;
use App\Models\QurbanCustomer;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\QurbanCustomerAddress;

class CustomerService
{
    public function getCustomers($farmId)
    {
        $customers = QurbanCustomer::where('farm_id', $farmId)->get();

        return $customers;
    }

    public function storeCustomer($request, $farmId)
    {
        $data = null;
        $error = false;

        DB::beginTransaction();

        try {

            // Cek apakah user ada di table farm_users
            $farmUser = FarmUser::where('user_id', $request['user_id'])->where('farm_id', $farmId)->first();

            if ($farmUser) {
                // Jika user tidak ada di farm_users, kembalikan error
                return [
                    'data' => null,
                    'error' => true
                ];
            }

            // Cek apakah user sudah ada di tabel customers
            $existingCustomer = QurbanCustomer::where('user_id', $request['user_id'])->first();

            if ($existingCustomer) {
                // Jika sudah ada, kembalikan data yang sudah ada
                return [
                    'data' => $existingCustomer,
                    'error' => false
                ];
            }

            // Simpan data ke tabel customers
            $customer = QurbanCustomer::create([
                'farm_id'           => $farmId,
                'user_id'           => $request['user_id'],
            ]);

            // Commit transaksi
            DB::commit();

            $data = $customer;

        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();

            $error = true;
        }

        return [
            'data' => $data,
            'error' => $error
        ];
    }

    public function getCustomer($farm_id , $id)
    {
        $customer = QurbanCustomer::where('farm_id' , $farm_id)->where('id',$id)->first();
        return $customer;
    }

    // public function updateCustomer($request, $farmId, $id)
    // {
    //     $data = null;
    //     $error = false;

    //     DB::beginTransaction();

    //     try {
    //         $customer = QurbanCustomer::findOrFail($id);

    //         // Simpan data ke tabel customers
    //         $customer->update([
    //             'name'              => $request['name'],
    //             'phone_number'      => $request['phone_number'],
    //         ]);

    //         // Commit transaksi
    //         DB::commit();

    //         $data = $customer;

    //     } catch (\Exception $e) {
    //         // Rollback transaksi jika terjadi kesalahan
    //         DB::rollBack();

    //         $error = true;
    //     }

    //     return [
    //         'data' => $data,
    //         'error' => $error
    //     ];
    // }

    public function deleteCustomer($farm_id, $id)
    {
        $error = false;

        DB::beginTransaction();

        try {
            $customer = QurbanCustomer::where('farm_id' , $farm_id)->where('id',$id)->first();

            $addresses = QurbanCustomerAddress::where('qurban_customer_id', $id)->get();
            foreach ($addresses as $address) {
                $address->delete();
            }

            $customer->delete();

            // Commit transaksi
            DB::commit();


        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();

            $error = true;
        }

        return [
            'error' => $error
        ];
    }

    public function getAddresses($farmId, $customerId)
    {
        $addresses = QurbanCustomerAddress::where('qurban_customer_id', $customerId)->get();

        return $addresses;
    }

    public function newUser($validatedData, $farmId)
    {
        // Begin database transaction
        DB::beginTransaction();

        try {
            // Create a new user
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'phone_number' => $validatedData['phone_number'],
                'password' => Hash::make($validatedData['password']),
                'email_verified_at' => Carbon::now()
            ]);

            $user->roles()->attach(RoleEnum::REGISTERED_USER->value, [
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $customer = QurbanCustomer::create([
                'farm_id'           => $farmId,
                'user_id'           => $user->id,
            ]);

            // Commit the transaction
            DB::commit();

            return $customer;

        } catch (\Exception $e) {

            // Rollback the transaction if an error occurs
            DB::rollBack();

            throw $e;
        }

    }
}
