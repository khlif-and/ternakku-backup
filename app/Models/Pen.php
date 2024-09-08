<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

}
