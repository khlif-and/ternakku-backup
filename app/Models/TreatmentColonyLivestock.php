<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TreatmentColonyLivestock extends Model
{
    use HasFactory;

    protected $table = 'treatment_colony_livestock';

    protected $guarded = [];

    public function treatmentColonyD()
    {
        return $this->belongsTo(TreatmentColonyD::class, 'treatment_colony_d_id');
    }

    public function livestock()
    {
        return $this->belongsTo(Livestock::class, 'livestock_id');
    }
}
