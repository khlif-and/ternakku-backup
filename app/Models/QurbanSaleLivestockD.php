<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QurbanSaleLivestockD extends Model
{
    use HasFactory;

    protected $table = 'qurban_sale_livestock_d';

    protected $fillable = [
        'qurban_sale_livestock_h_id',
        'qurban_customer_address_id',
        'livestock_id',
        'weight',
        'price_per_kg',
        'price_per_head',
        'delivery_plan_date',
    ];

    public function qurbanSaleLivestockH()
    {
        return $this->belongsTo(QurbanSaleLivestockH::class, 'qurban_sale_livestock_h_id');
    }

    public function qurbanCustomerAddress()
    {
        return $this->belongsTo(QurbanCustomerAddress::class, 'qurban_customer_address_id');
    }

    public function livestock()
    {
        return $this->belongsTo(Livestock::class, 'livestock_id');
    }
}
