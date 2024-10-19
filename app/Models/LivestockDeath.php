<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LivestockDeath extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_date',
        'transaction_number',
        'farm_id',
        'livestock_id',
        'disease_id',
        'indication',
        'notes',
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
        $prefix = "$year$month-LD-";

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

    // Relationship with Livestock model
    public function livestock()
    {
        return $this->belongsTo(Livestock::class);
    }

    // Relationship with Farm model
    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    public function disease()
    {
        return $this->belongsTo(Disease::class);
    }
}
