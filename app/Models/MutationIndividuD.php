<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MutationIndividuD extends Model
{
    use HasFactory;

    protected $table = 'mutation_individu_d';

    protected $guarded = [];

    public function mutationH()
    {
        return $this->belongsTo(MutationH::class, 'mutation_h_id');
    }

    public function livestock()
    {
        return $this->belongsTo(Livestock::class, 'livestock_id');
    }

    public function mutationIndividuItems()
    {
        return $this->hasMany(MutationIndividuItem::class, 'mutation_individu_d_id');
    }

    public function penFrom()
    {
        return $this->belongsTo(Pen::class, 'from' , 'id');
    }

    public function penTo()
    {
        return $this->belongsTo(Pen::class, 'to' , 'id');
    }
}
