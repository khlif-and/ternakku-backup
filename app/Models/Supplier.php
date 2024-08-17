<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'farm_id',
        'name',
        'phone_number',
        'region_id',
        'postal_code',
        'address_line',
        'longitude',
        'latitude'
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
