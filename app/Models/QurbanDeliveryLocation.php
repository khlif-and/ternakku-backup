<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QurbanDeliveryLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'qurban_delivery_instruction_h_id',
        'longitude',
        'latitude',
    ];

    public function qurbanDeliveryInstructionH()
    {
        return $this->belongsTo(QurbanDeliveryInstructionH::class, 'qurban_delivery_instruction_h_id');
    }

}
