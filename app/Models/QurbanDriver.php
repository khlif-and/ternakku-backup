<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QurbanDriver extends Model
{
    use HasFactory;

    protected $fillable = [
        'farm_id',
        'name',
        'description',
        'region_id',
        'postal_code',
        'address_line',
        'longitude',
        'latitude',
        'photo',
    ];

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }
}
