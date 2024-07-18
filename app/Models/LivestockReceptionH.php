<?php

namespace App\Models;

use App\Models\Farm;
use App\Models\Supplier;
use Illuminate\Support\Carbon;
use App\Models\LivestockReceptionD;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LivestockReceptionH extends Model
{
    use HasFactory;

    protected $table = 'livestock_reception_h';

    protected $fillable = [
        'farm_id',
        'transaction_number',
        'transaction_date',
        'supplier_id',
        'notes'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->transaction_number = $model->generateTransactionNumber();
        });
    }

    private function generateTransactionNumber()
    {
        $date = Carbon::now();
        $year = $date->format('y'); // last two digits of the year
        $month = $date->format('m'); // month with leading zero
        $prefix = "$year$month-LR-";

        // Get the last transaction number for the current month and year
        $lastTransaction = self::whereYear('transaction_date', $date->year)
            ->whereMonth('transaction_date', $date->month)
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

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function livestockReceptionD()
    {
        return $this->hasMany(LivestockReceptionD::class, 'livestock_reception_h_id');
    }
}
