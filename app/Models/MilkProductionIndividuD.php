<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MilkProductionIndividuD extends Model
{
    use HasFactory;

    protected $table = 'milk_production_individu_d';

    protected $guarded = [];

    public function milkProductionH()
    {
        return $this->belongsTo(MilkProductionH::class, 'milk_production_h_id');
    }

    public function livestock()
    {
        return $this->belongsTo(Livestock::class, 'livestock_id');
    }
}
