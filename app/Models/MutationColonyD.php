<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MutationColonyD extends Model
{
    use HasFactory;

    protected $table = 'mutation_colony_d';

    protected $guarded = [];

    public function mutationH()
    {
        return $this->belongsTo(MutationH::class, 'mutation_h_id');
    }

    public function pen()
    {
        return $this->belongsTo(Pen::class, 'pen_id');
    }

    public function mutationColonyItems()
    {
        return $this->hasMany(MutationColonyItem::class, 'mutation_colony_d_id');
    }

    public function livestocks()
    {
        return $this->belongsToMany(Livestock::class, 'mutation_colony_livestock', 'mutation_colony_d_id', 'livestock_id');
    }
}
