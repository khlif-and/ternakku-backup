<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PregnantCheckD extends Model
{
    use HasFactory;

    protected $table = 'pregnant_check_d';

    protected $guarded = [];

    public function pregnantCheck()
    {
        return $this->belongsTo(PregnantCheck::class, 'pregnant_check_id');
    }

    public function reproductionCycle()
    {
        return $this->belongsTo(ReproductionCycle::class, 'reproduction_cycle_id');
    }
}
