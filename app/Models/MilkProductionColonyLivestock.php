<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MilkProductionColonyLivestock extends Model
{
    use HasFactory;

    protected $table = 'milk_production_colony_livestock';

    protected $guarded = [];

    public function milkProductionColonyD()
    {
        return $this->belongsTo(MilkProductionColonyD::class, 'milk_production_colony_d_id');
    }

    public function livestock()
    {
        return $this->belongsTo(Livestock::class, 'livestock_id');
    }
}
