<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MilkAnalysisIndividuD extends Model
{
    use HasFactory;

    protected $table = 'milk_analysis_individu_d';

    protected $guarded = [];

    public function milkAnalysisH()
    {
        return $this->belongsTo(MilkAnalysisH::class, 'milk_analysis_h_id');
    }

    public function livestock()
    {
        return $this->belongsTo(Livestock::class, 'livestock_id');
    }
}
