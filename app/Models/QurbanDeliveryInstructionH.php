<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QurbanDeliveryInstructionH extends Model
{
    use HasFactory;

    protected $table = 'qurban_delivery_instruction_h';

    protected $fillable = [
        'farm_id',
        'delivery_date',
        'driver_id',
        'fleet_id',
        'status',
    ];

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function fleet()
    {
        return $this->belongsTo(QurbanFleet::class, 'fleet_id');
    }

    public function qurbanDeliveryInstructionD()
    {
        return $this->hasMany(QurbanDeliveryInstructionD::class, 'qurban_delivery_instruction_h_id');
    }
}
