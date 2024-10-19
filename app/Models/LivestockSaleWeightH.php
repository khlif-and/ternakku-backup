<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LivestockSaleWeightH extends Model
{
    use HasFactory;

    protected $table = 'livestock_sale_weight_h';

    protected $fillable = [
        'farm_id',
        'transaction_number',
        'transaction_date',
        'notes',
        'customer'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->transaction_number = $model->generateTransactionNumber($model->transaction_date , $model->farm_id);
        });
    }

    private function generateTransactionNumber($transaction_date, $farmId)
    {
        $date = Carbon::parse($transaction_date);

        $year = $date->format('y'); // last two digits of the year
        $month = $date->format('m'); // month with leading zero
        $prefix = "$year$month-SW-";

        // Get the last transaction number for the current month and year
        $lastTransaction = self::whereYear('transaction_date', $date->year)
            ->whereMonth('transaction_date', $date->month)
            ->where('farm_id' , $farmId)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($lastTransaction) {
            // Extract the number and increment it
            $lastNumber = (int) substr($lastTransaction->transaction_number, -3);
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            // Start with 001 if there are no transactions yet
            $newNumber = '001';
        }

        return $prefix . $newNumber;
    }

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    public function livestockSaleWeightD()
    {
        return $this->hasMany(LivestockSaleWeightD::class, 'livestock_sale_weight_h_id');
    }
}
