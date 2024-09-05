<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedMedicinePurchaseD extends Model
{
    use HasFactory;

    protected $table = 'feed_medicine_purchase_d';

    protected $fillable = [
        'feed_medicine_purchase_h_id',
        'purchase_type',
        'item_name',
        'quantity',
        'unit',
        'price_per_unit',
        'total_price',
    ];

    public function feedMedicinePurchaseH()
    {
        return $this->belongsTo(FeedMedicinePurchaseH::class, 'feed_medicine_purchase_h_id');
    }

}
