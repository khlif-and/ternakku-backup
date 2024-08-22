<?php

namespace App\Models;

use App\Models\LivestockExpense;
use App\Enums\LivestockExpenseTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FeedingIndividuD extends Model
{
    use HasFactory;

    protected $table = 'feeding_individu_d';

    protected $guarded = [];

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
            $model->feed_material_total = $model->feed_material_qty_kg * $model->feed_material_price_kg;

            $model->total_cost = $model->forage_total + $model->concentrate_total + $model->feed_material_total;

            LivestockExpense::updateOrCreate(
                [
                    'livestock_id' => $model->livestock_id,
                    'livestock_expense_type_id' => LivestockExpenseTypeEnum::FEEDING->value,
                ],
                [
                    'amount' => $model->total_cost,
                ]
            );
        });
    }
}
