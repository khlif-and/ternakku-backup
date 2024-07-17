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
}
