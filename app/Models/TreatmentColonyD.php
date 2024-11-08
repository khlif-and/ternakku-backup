<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TreatmentColonyD extends Model
{
    use HasFactory;

    protected $table = 'treatment_colony_d';

    protected $guarded = [];

    public function treatmentH()
    {
        return $this->belongsTo(TreatmentH::class, 'treatment_h_id');
    }

    public function pen()
    {
        return $this->belongsTo(Pen::class, 'pen_id');
    }

    public function disease()
    {
        return $this->belongsTo(Disease::class, 'disease_id');
    }

    public function treatmentColonyMedicineItems()
    {
        return $this->hasMany(TreatmentColonyMedicineItem::class, 'treatment_colony_d_id');
    }

    public function treatmentColonyTreatmentItems()
    {
        return $this->hasMany(TreatmentColonyTreatmentItem::class, 'treatment_colony_d_id');
    }

    public function livestocks()
    {
        return $this->belongsToMany(Livestock::class, 'treatment_colony_livestock', 'treatment_colony_d_id', 'livestock_id');
    }
}
