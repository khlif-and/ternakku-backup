<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TreatmentColonyMedicineItem extends Model
{
    use HasFactory;

    protected $table = 'treatment_colony_medicine_item';

    protected $guarded = [];

    public function treatmentColonyD()
    {
        return $this->belongsTo(TreatmentColonyD::class, 'treatment_colony_d_id');
    }
}
