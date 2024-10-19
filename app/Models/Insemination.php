<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Insemination extends Model
{
    use HasFactory;

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

        $year = $date->format('y'); // last two digits of the year
        $month = $date->format('m'); // month with leading zero
        $prefix = $type == 'artificial' ? "$year$month-AI-" : "$year$month-NI-";

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

    public function inseminationArtificial()
    {
        return $this->hasMany(InseminationArtificial::class, 'insemination_id');
    }

    public function inseminationNatural()
    {
        return $this->hasMany(InseminationNatural::class, 'insemination_id');
    }
}
