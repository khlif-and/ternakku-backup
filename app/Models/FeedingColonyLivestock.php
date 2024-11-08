<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedingColonyLivestock extends Model
{
    use HasFactory;

    protected $table = 'feeding_colony_livestock';

    protected $guarded = [];

    public function feedingColonyD()
    {
        return $this->belongsTo(FeedingColonyD::class, 'feeding_colony_d_id');
    }

    public function livestock()
    {
        return $this->belongsTo(Livestock::class, 'livestock_id');
    }
}
