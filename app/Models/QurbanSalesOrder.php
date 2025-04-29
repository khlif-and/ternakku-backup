<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QurbanSalesOrder extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function qurbanCustomer()
    {
        return $this->belongsTo(QurbanCustomer::class);
    }

    public function qurbanSalesOrderD()
    {
        return $this->hasMany(QurbanSalesOrderD::class, 'qurban_sales_order_id');
    }
}
