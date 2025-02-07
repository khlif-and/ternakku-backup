<?php

namespace App\Services\Qurban;

use App\Models\QurbanCustomer;
use Illuminate\Support\Facades\DB;

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
}
