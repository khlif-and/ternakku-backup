<?php

namespace App\Services\Qurban;

use App\Models\QurbanPrice;
use Illuminate\Support\Facades\DB;


class PriceService
{
    public function getPrices($request, $farmId)
    {
        $query = QurbanPrice::where('farm_id', $farmId);

        if (request()->has('hijri_year')) {
            $query->where('hijri_year', request()->get('hijri_year'));
        }
    
        $prices = $query->orderBy('start_weight')->get();
    
        return $prices;    
    }

    public function getPrice($farmId , $priceId)
    {
        $price = QurbanPrice::where('farm_id', $farmId)->where('id' , $priceId)->first();

        return $price;
    }

    public function storePrice($farmId, $request)
    {
        $data = null;
        $error = false;

        $validated = $request->validated();

        DB::beginTransaction();

        try {

            // Simpan data ke tabel Prices
            $price = QurbanPrice::create([
                'name'              => $validated['name'],
                'farm_id'           => $farmId,
                'hijri_year'        => $validated['hijri_year'],
                'livestock_type_id' => $validated['livestock_type_id'],
                'start_weight'      => $validated['start_weight'],
                'end_weight'        => $validated['end_weight'],
                'price_per_kg'      => $validated['price_per_kg'],            
            ]);

            // Commit transaksi
            DB::commit();

            $data = $price;
        } catch (\Exception $e) {
            dd($e);
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();

            $error = true;
        }

        return [
            'data' => $data,
            'error' => $error
        ];
    }

    public function updatePrice($farmId, $priceId, $request)
    {
        $validated = $request->validated();

        $error = false;
        $data = null;

        DB::beginTransaction();

        try {
            $price = QurbanPrice::where('farm_id' , $farmId)->where('id' , $priceId)->first();


            $price->update([
                'name'              => $validated['name'],
                'hijri_year'        => $validated['hijri_year'],
                'livestock_type_id' => $validated['livestock_type_id'],
                'start_weight'      => $validated['start_weight'],
                'end_weight'        => $validated['end_weight'],
                'price_per_kg'      => $validated['price_per_kg'],            
            ]);

            $data = $price;

            DB::commit();

        } catch (\Exception $e) {
            dd($e);
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();

            $error = true;
        }

        return [
            'data' => $data,
            'error' => $error
        ];
    }

    public function deletePrice($farm_id, $priceId)
    {
        $error = false;

        try {
            $price = QurbanPrice::where('farm_id' , $farm_id)->where('id',$priceId)->first();

            if ($price->photo) {
                deleteNeoObject($price->photo);
            }

            $price->delete();

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

    public function getEstimationPrice($request, $farmId)
    {
        $validated = $request->validated();

        $price = QurbanPrice::where('farm_id', $farmId)
            ->where('livestock_type_id', $validated['livestock_type_id'])
            ->where('hijri_year', $validated['hijri_year'])
            ->where('start_weight', '<=', $validated['weight'])
            ->where('end_weight', '>=', $validated['weight'])
            ->orderBy('start_weight')
            ->first();

        if (!$price) {
            return [
                'error' => true,
                'data' => null,
            ];
        }
    
        return [
            'error' => false,
            'data' => $price,
        ];    
    }

}
