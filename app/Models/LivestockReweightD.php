<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LivestockReweightD extends Model
{
    use HasFactory;

    protected $table = 'livestock_reweight_d';

    protected $fillable = [
        'livestock_reweight_h_id',
        'livestock_id',
        'weight',
        'notes',
        'photo',
    ];

    public function livestockReweightH()
    {
        return $this->belongsTo(LivestockReweightH::class, 'livestock_reweight_h_id');
    }

    public function livestock()
    {
        return $this->belongsTo(Livestock::class, 'livestock_id');
    }
}
