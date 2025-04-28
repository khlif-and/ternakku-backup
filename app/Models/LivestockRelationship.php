<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LivestockRelationship extends Model
{
    use HasFactory;

    public function parent()
    {
        return $this->belongsTo(Livestock::class, 'parent_id');
    }

    public function sireBreed()
    {
        return $this->belongsTo(LivestockBreed::class, 'sire_breed_id');
    }
}
