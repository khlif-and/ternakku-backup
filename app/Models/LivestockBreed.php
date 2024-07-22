<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LivestockBreed extends Model
{
    use HasFactory;

    protected $fillable = [
        'livestock_type_id',
        'name',
    ];

    public function livestockType()
    {
        return $this->belongsTo(LivestockType::class);
    }
}
