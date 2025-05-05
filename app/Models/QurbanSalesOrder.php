<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QurbanSalesOrder extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->transaction_number = $model->generateTransactionNumber($model->order_date,  $model->farm_id);
        });
    }

    private function generateTransactionNumber($transactionDate, $farmId)
    {
        $date = Carbon::parse($transactionDate);

        $code =  'QSO';
        $year = $date->format('y'); // last two digits of the year
        $month = $date->format('m'); // month with leading zero
        $prefix = "$year$month-$code-";

        // Get the last transaction number for the current month and year
        $lastTransaction = self::whereYear('order_date', $date->year)
            ->whereMonth('order_date', $date->month)
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

    public function qurbanCustomer()
    {
        return $this->belongsTo(QurbanCustomer::class);
    }

    public function qurbanSalesOrderD()
    {
        return $this->hasMany(QurbanSalesOrderD::class, 'qurban_sales_order_id');
    }
}
