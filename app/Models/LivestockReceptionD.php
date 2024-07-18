<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LivestockReceptionD extends Model
{
    use HasFactory;

    protected $table = 'livestock_reception_d';

    protected $fillable = [
        'livestock_reception_h_id',
        'eartag_number',
        'rfid_number',
        'type_id',
        'group_id',
        'breed_id',
        'sex_id',
        'pen_id',
        'age_years',
        'age_months',
        'weight',
        'price_per_kg',
        'price_per_head',
        'notes',
        'photo',
        'farm_id',
    ];

    public function livestockReceptionH()
    {
        return $this->belongsTo(LivestockReceptionH::class, 'livestock_reception_h_id');
    }

    public function type()
    {
        return $this->belongsTo(LivestockType::class, 'type_id');
    }

    public function group()
    {
        return $this->belongsTo(LivestockGroup::class, 'group_id');
    }

    public function breed()
    {
        return $this->belongsTo(LivestockBreed::class, 'breed_id');
    }

    public function sex()
    {
        return $this->belongsTo(LivestockSex::class, 'sex_id');
    }

    public function pen()
    {
        return $this->belongsTo(Pen::class, 'pen_id');
    }
}
