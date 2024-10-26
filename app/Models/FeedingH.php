<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FeedingH extends Model
{
    use HasFactory;

    protected $table = 'feeding_h';

    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->transaction_number = $model->generateTransactionNumber($model->type , $model->transaction_date,  $model->farm_id);
        });
    }

    private function generateTransactionNumber($type , $transactionDate, $farmId)
    {
        $date = Carbon::parse($transactionDate);

        $code =  $type == 'colony' ? 'FC' : 'FI';
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

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    public function feedingIndividuD()
    {
        return $this->hasMany(FeedingIndividuD::class, 'feeding_h_id');
    }

    public function feedingColonyD()
    {
        return $this->hasMany(FeedingColonyD::class, 'feeding_h_id');
    }
}
