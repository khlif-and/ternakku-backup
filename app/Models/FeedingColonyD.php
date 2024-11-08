<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedingColonyD extends Model
{
    use HasFactory;

    protected $table = 'feeding_colony_d';

    protected $guarded = [];

    public function feedingH()
    {
        return $this->belongsTo(FeedingH::class, 'feeding_h_id');
    }

    public function pen()
    {
        return $this->belongsTo(Pen::class, 'pen_id');
    }

    public function feedingColonyItems()
    {
        return $this->hasMany(FeedingColonyItem::class, 'feeding_colony_d_id');
    }

    public function livestocks()
    {
        return $this->belongsToMany(Livestock::class, 'feeding_colony_livestock', 'feeding_colony_d_id', 'livestock_id');
    }
}
