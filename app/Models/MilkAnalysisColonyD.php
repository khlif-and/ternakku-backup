<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MilkAnalysisColonyD extends Model
{
    use HasFactory;

    protected $table = 'milk_analysis_colony_d';

    protected $guarded = [];

    public function milkAnalysisH()
    {
        return $this->belongsTo(MilkAnalysisH::class, 'milk_analysis_h_id');
    }

    public function pen()
    {
        return $this->belongsTo(Pen::class, 'pen_id');
    }

    public function milkAnalysisColonyItems()
    {
        return $this->hasMany(MilkAnalysisColonyItem::class, 'milk_analysis_colony_d_id');
    }

    public function livestocks()
    {
        return $this->belongsToMany(Livestock::class, 'milk_analysis_colony_livestock', 'milk_analysis_colony_d_id', 'livestock_id');
    }
}
