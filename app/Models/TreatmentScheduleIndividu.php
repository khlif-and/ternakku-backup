<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TreatmentScheduleIndividu extends Model
{
    use HasFactory;

    protected $table = 'treatment_schedule_individu';

    protected $guarded = [];

    public function treatmentSchedule()
    {
        return $this->belongsTo(TreatmentSchedule::class, 'treatment_schedule_id');
    }

    public function livestock()
    {
        return $this->belongsTo(Livestock::class, 'livestock_id');
    }
}
