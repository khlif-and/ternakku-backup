<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MutationColonyLivestock extends Model
{
    use HasFactory;

    protected $table = 'mutation_colony_livestock';

    protected $guarded = [];

    public function mutationColonyD()
    {
        return $this->belongsTo(MutationColonyD::class, 'mutation_colony_d_id');
    }

    public function livestock()
    {
        return $this->belongsTo(Livestock::class, 'livestock_id');
    }
}
