<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FarmDetail extends Model
{
    use HasFactory;

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function regency()
    {
        return $this->belongsTo(Regency::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function village()
    {
        return $this->belongsTo(Village::class);
    }

    // Definisikan accessor untuk logo
    public function getLogoAttribute($value)
    {
        return getNeoObject($value);
    }

    // Definisikan mutator untuk logo
    public function setLogoAttribute($value)
    {
        $this->attributes['logo'] = getNeoObject($value);
    }

}
