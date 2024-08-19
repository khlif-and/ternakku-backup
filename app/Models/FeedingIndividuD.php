<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedingIndividuD extends Model
{
    use HasFactory;

    protected $table = 'feeding_inividu_d';

    public function feedingH()
    {
        return $this->belongsTo(FeedingH::class, 'feeding_h_id');
    }

    public function livestock()
    {
        return $this->belongsTo(Livestock::class, 'livestock_id');
    }

    protected static function booted()
    {
        static::saving(function ($model) {
            $model->forage_total = $model->forage_qty_kg * $model->forage_price_kg;
            $model->concentrate_total = $model->concentrate_qty_kg * $model->concentrate_price_kg;
            $model->ingredient_total = $model->ingredient_qty_kg * $model->ingredient_price_kg;

            $model->total_cost = $model->forage_total + $model->concentrate_total + $model->ingredient_total;
        });
    }
}
