<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InseminationNatural extends Model
{
    use HasFactory;

    protected $table = 'insemination_natural';

    protected $guarded = [];

    public function insemination()
    {
        return $this->belongsTo(Insemination::class, 'insemination_id');
    }

    public function reproductionCycle()
    {
        return $this->belongsTo(ReproductionCycle::class, 'reproduction_cycle_id');
    }

    public function sireBreed()
    {
        return $this->belongsTo(LivestockBreed::class, 'sire_breed_id');
    }
}
