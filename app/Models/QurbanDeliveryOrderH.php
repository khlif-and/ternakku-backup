<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QurbanDeliveryOrderH extends Model
{
    use HasFactory;

    protected $table = 'qurban_delivery_order_h';

    protected $fillable = [
        'farm_id',
        'transaction_number',
        'transaction_date',
        'qurban_customer_address_id',
        'qurban_sale_livestock_h_id',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->transaction_number = $model->generateTransactionNumber($model->transaction_date,  $model->farm_id);
        });
    }

    private function generateTransactionNumber($transactionDate, $farmId)
    {
        $date = Carbon::parse($transactionDate);

        $code =  'QSJ';
        $year = $date->format('y'); // last two digits of the year
        $month = $date->format('m'); // month with leading zero
        $prefix = "$year$month-$code-";

        // Get the last transaction number for the current month and year
        $lastTransaction = self::whereYear('transaction_date', $date->year)
            ->whereMonth('transaction_date', $date->month)
            ->where('farm_id' , $farmId)
            ->where('transaction_number' , 'like' , "%$code%")
            ->orderBy('transaction_number', 'desc')
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

    // Relasi ke detail
    public function qurbanDeliveryOrderD()
    {
        return $this->hasMany(QurbanDeliveryOrderD::class, 'qurban_delivery_order_h_id');
    }

    public function qurbanCustomerAddress()
    {
        return $this->belongsTo(QurbanCustomerAddress::class, 'qurban_customer_address_id');
    }

    public function qurbanSaleLivestockH()
    {
        return $this->belongsTo(QurbanSaleLivestockH::class, 'qurban_sale_livestock_h_id');
    }

    public function farm()
    {
        return $this->belongsTo(Farm::class, 'farm_id');
    }
}
