<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MilkAnalysisColonyLivestock extends Model
{
    use HasFactory;

    protected $table = 'milk_analysis_colony_livestock';

    protected $guarded = [];

    public function milkAnalysisColonyD()
    {
        return $this->belongsTo(MilkAnalysisColonyD::class, 'milk_analysis_colony_d_id');
    }

    public function livestock()
    {
        return $this->belongsTo(Livestock::class, 'livestock_id');
    }
}
