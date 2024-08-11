<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QurbanContract extends Model
{
    use HasFactory;

    protected $fillable = [
        'qurban_saving_registration_id',
        'livestock_breed_id',
        'weight',
        'price_per_kg',
        'province_id',
        'regency_id',
        'district_id',
        'village_id',
        'postal_code',
        'address_line',
        'longitude',
        'latitude',
        'contract_date',
        'down_payment',
        'farm_id',
        'estimated_delivery_date',
    ];

    public function qurbanSavingRegistration()
    {
        return $this->belongsTo(QurbanSavingRegistration::class);
    }

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    public function livestockBreed()
    {
        return $this->belongsTo(LivestockBreed::class);
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
}
