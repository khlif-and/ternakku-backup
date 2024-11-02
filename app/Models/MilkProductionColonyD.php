<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MilkProductionColonyD extends Model
{
    use HasFactory;

    protected $table = 'milk_production_colony_d';

    protected $guarded = [];

    public function milkProductionH()
    {
        return $this->belongsTo(MilkProductionH::class, 'milk_production_h_id');
    }

    public function pen()
    {
        return $this->belongsTo(Pen::class, 'pen_id');
    }

    public function milkProductionColonyItems()
    {
        return $this->hasMany(MilkProductionColonyItem::class, 'milk_production_colony_d_id');
    }

    public function livestocks()
    {
        return $this->belongsToMany(Livestock::class, 'milk_production_colony_livestock', 'milk_production_colony_d_id', 'livestock_id');
    }
}
