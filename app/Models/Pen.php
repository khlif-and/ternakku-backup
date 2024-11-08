<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\LivestockClassificationEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pen extends Model
{
    use HasFactory;

    protected $fillable = [
        'farm_id',
        'name',
        'area',
        'capacity',
        'photo',
    ];

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    public function livestockReceptionDs()
    {
        return $this->hasMany(LivestockReceptionD::class);
    }

    public function livestocks()
    {
        return $this->hasMany(Livestock::class);
    }

    public function getPopulationAttribute()
    {
        return $this->livestocks()->alive()->count();
    }

    public function livestockLactations()
    {
        return $this->livestocks()
            ->whereIn('livestock_classification_id' , [
                LivestockClassificationEnum::LAKTASI_BUNTING ,
                LivestockClassificationEnum::LAKTASI_KOSONG
            ])->get();
    }
}
