<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QurbanDeliveryInstructionH extends Model
{
    use HasFactory;

    protected $table = 'qurban_delivery_instruction_h';

    protected $fillable = [
        'farm_id',
        'delivery_date',
        'driver_id',
        'fleet_id',
        'status',
        'transaction_number'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->transaction_number = $model->generateTransactionNumber(now()->format('Y-m-d'),  $model->farm_id);
        });
    }

    private function generateTransactionNumber($transactionDate, $farmId)
    {
        $date = Carbon::parse($transactionDate);

        $code =  'QPK';
        $year = $date->format('y'); // last two digits of the year
        $month = $date->format('m'); // month with leading zero
        $prefix = "$year$month-$code-";

        // Get the last transaction number for the current month and year
        $lastTransaction = self::whereYear('delivery_date', $date->year)
            ->whereMonth('delivery_date', $date->month)
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

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function fleet()
    {
        return $this->belongsTo(QurbanFleet::class, 'fleet_id');
    }

    public function qurbanDeliveryInstructionD()
    {
        return $this->hasMany(QurbanDeliveryInstructionD::class, 'qurban_delivery_instruction_h_id');
    }

    public function deliveryOrders()
    {
        return $this->belongsToMany(
            QurbanDeliveryOrderH::class,
            'qurban_delivery_instruction_d',
            'qurban_delivery_instruction_h_id',
            'qurban_delivery_order_h_id'
        );
    }
}
