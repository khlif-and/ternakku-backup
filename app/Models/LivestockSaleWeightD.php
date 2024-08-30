<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LivestockSaleWeightD extends Model
{
    use HasFactory;

    protected $table = 'livestock_sale_weight_d';

    protected $fillable = [
        'livestock_sale_weight_h_id',
        'livestock_id',
        'weight',
        'price_per_kg',
        'price_per_head',
        'notes',
        'photo',
    ];

    public function livestockSaleWeightH()
    {
        return $this->belongsTo(LivestockSaleWeightH::class, 'livestock_sale_weight_h_id');
    }

    public function livestock()
    {
        return $this->belongsTo(Livestock::class, 'livestock_id');
    }

}
