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

    public function feedingIndividuItems()
    {
        return $this->hasMany(FeedingIndividuItem::class, 'feeding_individu_d_id');
    }
}
