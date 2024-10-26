<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedingColonyItem extends Model
{
    use HasFactory;

    protected $table = 'feeding_colony_item';

    protected $guarded = [];

    public function feedingColonyD()
    {
        return $this->belongsTo(FeedingColonyD::class, 'feeding_colony_d_id');
    }
}
