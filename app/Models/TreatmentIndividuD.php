<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TreatmentIndividuD extends Model
{
    use HasFactory;

    protected $table = 'treatment_individu_d';

    protected $guarded = [];

    public function treatmentH()
    {
        return $this->belongsTo(TreatmentH::class, 'treatment_h_id');
    }

    public function livestock()
    {
        return $this->belongsTo(Livestock::class, 'livestock_id');
    }

    public function disease()
    {
        return $this->belongsTo(Disease::class, 'disease_id');
    }

    public function treatmentIndividuMedicineItems()
    {
        return $this->hasMany(TreatmentIndividuMedicineItem::class, 'treatment_individu_d_id');
    }

    public function treatmentIndividuTreatmentItems()
    {
        return $this->hasMany(TreatmentIndividuTreatmentItem::class, 'treatment_individu_d_id');
    }
}
