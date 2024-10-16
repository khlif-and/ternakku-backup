<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReproductionCycle extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function livestock()
    {
        return $this->belongsTo(Livestock::class, 'livestock_id');
    }

    public function inseminationNatural()
    {
        return $this->hasOne(InseminationNatural::class , 'reproduction_cycle_id');
    }

    public function inseminationArtificial()
    {
        return $this->hasOne(InseminationArtificial::class , 'reproduction_cycle_id');
    }

}
