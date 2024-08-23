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
            // Menghitung total baru
            $model->forage_total = $model->forage_qty_kg * $model->forage_price_kg;
            $model->concentrate_total = $model->concentrate_qty_kg * $model->concentrate_price_kg;
            $model->feed_material_total = $model->feed_material_qty_kg * $model->feed_material_price_kg;
            $model->total_cost = $model->forage_total + $model->concentrate_total + $model->feed_material_total;

            $livestockExpense = LivestockExpense::where('livestock_id', $model->livestock_id)
                ->where('livestock_expense_type_id', LivestockExpenseTypeEnum::FEEDING->value)
                ->first();

            if ($model->exists) {
                // Jika sedang di-update, kurangi amount dengan total_cost sebelumnya
                $originalTotalCost = $model->getOriginal('total_cost');
                $livestockExpense->amount -= $originalTotalCost;
            }

            if ($livestockExpense) {
                // Tambahkan total_cost baru ke amount
                $livestockExpense->amount += $model->total_cost;
                $livestockExpense->save();
            } else {
                // Buat entri baru jika LivestockExpense belum ada
                LivestockExpense::create([
                    'livestock_id' => $model->livestock_id,
                    'livestock_expense_type_id' => LivestockExpenseTypeEnum::FEEDING->value,
                    'amount' => $model->total_cost,
                ]);
            }
        });

        static::deleting(function ($model) {
            // Cari LivestockExpense berdasarkan livestock_id dan tipe biaya FEEDING
            $livestockExpense = LivestockExpense::where('livestock_id', $model->livestock_id)
                ->where('livestock_expense_type_id', LivestockExpenseTypeEnum::FEEDING->value)
                ->first();

            if ($livestockExpense) {
                // Kurangi total_cost dari amount
                $livestockExpense->amount -= $model->total_cost;

                if ($livestockExpense->amount <= 0) {
                    // Jika amount menjadi 0 atau kurang, hapus LivestockExpense atau atur amount menjadi 0
                    $livestockExpense->delete(); // Atau bisa juga $livestockExpense->amount = 0; $livestockExpense->save();
                } else {
                    // Simpan perubahan amount
                    $livestockExpense->save();
                }
            }
        });
    }

}
