<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QurbanDeliveryInstructionD extends Model
{
    use HasFactory;

    protected $table = 'qurban_delivery_instruction_d';

    protected $fillable = [
        'qurban_delivery_instruction_h_id',
        'qurban_delivery_order_h_id',
    ];

    public function qurbanDeliveryInstructionH()
    {
        return $this->belongsTo(QurbanDeliveryInstructionH::class, 'qurban_delivery_instruction_h_id');
    }

    public function qurbanDeliveryOrderH()
    {
        return $this->belongsTo(QurbanDeliveryOrderH::class, 'qurban_delivery_order_h_id');
    }
}
