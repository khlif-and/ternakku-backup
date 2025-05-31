<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QurbanFleet extends Model
{
    use HasFactory;

    protected $fillable = [
        'farm_id',
        'name',
        'police_number',
        'photo',
    ];

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    public function positions()
    {
        return $this->hasMany(QurbanFleetPosition::class);
    }

    public function latestPosition()
    {
        return $this->hasOne(QurbanFleetPosition::class)->latestOfMany();
    }
}
