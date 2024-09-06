<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedingIndividuItem extends Model
{
    use HasFactory;

    protected $table = 'feeding_individu_item';

    protected $guarded = [];

    public function feedingIndividuD()
    {
        return $this->belongsTo(FeedingIndividuD::class, 'feeding_individu_d_id');
    }
}
