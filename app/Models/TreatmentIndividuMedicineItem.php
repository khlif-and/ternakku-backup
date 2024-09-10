<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TreatmentIndividuMedicineItem extends Model
{
    use HasFactory;

    protected $table = 'treatment_individu_medicine_item';

    protected $guarded = [];

    public function treatmentIndividuD()
    {
        return $this->belongsTo(TreatmentIndividuD::class, 'treatment_individu_d_id');
    }
}
