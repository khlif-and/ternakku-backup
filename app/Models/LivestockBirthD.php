<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LivestockBirthD extends Model
{
    use HasFactory;

    protected $table = 'livestock_birth_d';

    protected $guarded = [];

    public function livestockBirth()
    {
        return $this->belongsTo(LivestockBirth::class, 'livestock_birth_id');
    }

    public function livestockSex()
    {
        return $this->belongsTo(LivestockSex::class, 'livestock_sex_id');
    }

    public function disease()
    {
        return $this->belongsTo(Disease::class, 'disease_id');
    }
}
