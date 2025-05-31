<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QurbanFleetPosition extends Model
{
    use HasFactory;

    protected $fillable = [
        'qurban_fleet_id',
        'latitude',
        'longitude',
    ];

    public function qurbanFleet()
    {
        return $this->belongsTo(QurbanFleet::class);
    }
}
