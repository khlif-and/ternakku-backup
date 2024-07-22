<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LivestockType extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function livestockBreeds()
    {
        return $this->hasMany(LivestockBreed::class);
    }
}
