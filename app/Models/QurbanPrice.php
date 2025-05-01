<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QurbanPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'farm_id',
        'hijri_year',
        'livestock_type_id',
        'name',
        'start_weight',
        'end_weight',
        'price_per_kg',
    ];

    // Relasi ke model Farm
    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    // Relasi ke model LivestockType
    public function livestockType()
    {
        return $this->belongsTo(LivestockType::class);
    }
}
