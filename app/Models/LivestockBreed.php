<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LivestockBreed extends Model
{
    use HasFactory;

    protected $fillable = [
        'farm_id',
        'livestock_type_id',
        'name',
    ];

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    public function livestockType()
    {
        return $this->belongsTo(LivestockType::class);
    }
}
