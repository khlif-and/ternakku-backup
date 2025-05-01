<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QurbanSalesOrderD extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'qurban_sales_order_d';

    public function qurbanSalesOrder()
    {
        return $this->belongsTo(QurbanSalesOrder::class, 'qurban_sales_order_id');
    }

    public function livestockType()
    {
        return $this->belongsTo(LivestockType::class, 'livestock_type_id');
    }

}
