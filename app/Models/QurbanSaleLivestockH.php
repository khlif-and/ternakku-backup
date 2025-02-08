<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QurbanSaleLivestockH extends Model
{
    use HasFactory;

    protected $table = 'qurban_sale_livestock_h';

    protected $fillable = [
        'qurban_customer_id',
        'qurban_sales_order_id',
        'transaction_number',
        'transaction_date',
        'notes',
    ];

    /**
     * Relasi ke model QurbanCustomer.
     */
    public function customer()
    {
        return $this->belongsTo(QurbanCustomer::class, 'qurban_customer_id');
    }

    /**
     * Relasi ke model QurbanSalesOrder.
     */
    public function salesOrder()
    {
        return $this->belongsTo(QurbanSalesOrder::class, 'qurban_sales_order_id');
    }
}
