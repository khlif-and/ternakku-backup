<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QurbanDeliveryOrderD extends Model
{
    use HasFactory;

    protected $table = 'qurban_delivery_order_d';

    protected $fillable = [
        'qurban_delivery_order_h_id',
        'livestock_id',
    ];

    // Relasi ke header surat jalan
    public function qurbanDeliveryOrderH()
    {
        return $this->belongsTo(QurbanDeliveryOrderH::class, 'qurban_delivery_order_h_id');
    }

    // Relasi ke livestock
    public function livestock()
    {
        return $this->belongsTo(Livestock::class, 'livestock_id');
    }

    public function qurbanSaleLivestockD()
    {
        return $this->belongsTo(QurbanSaleLivestockD::class, 'livestock_id' , 'livestock_id');
    }

}
