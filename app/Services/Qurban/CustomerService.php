<?php

namespace App\Services\Qurban;

use App\Models\QurbanCustomer;
use Illuminate\Support\Facades\DB;
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
            // Simpan data ke tabel customers
            $customer = QurbanCustomer::create([
                'farm_id'           => $farmId,
                'name'              => $request['name'],
                'phone_number'      => $request['phone_number'],
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

    public function updateCustomer($request, $farmId, $id)
    {
        $data = null;
        $error = false;

        DB::beginTransaction();

        try {
            $customer = QurbanCustomer::findOrFail($id);

            // Simpan data ke tabel customers
            $customer->update([
                'name'              => $request['name'],
                'phone_number'      => $request['phone_number'],
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
}
