<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InseminationArtificial extends Model
{
    use HasFactory;

    protected $table = 'insemination_artificial';

    protected $guarded = [];

    public function insemination()
    {
        return $this->belongsTo(Insemination::class, 'insemination_id');
    }

    public function reproductionCycle()
    {
        return $this->belongsTo(ReproductionCycle::class, 'reproduction_cycle_id');
    }

    public function semenBreed()
    {
        return $this->belongsTo(LivestockBreed::class, 'semen_breed_id');
    }
}
