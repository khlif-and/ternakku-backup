<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedMedicinePurchaseItem extends Model
{
    use HasFactory;

    protected $table = 'feed_medicine_purchase_item';

    protected $fillable = [
        'feed_medicine_purchase_id',
        'purchase_type',
        'item_name',
        'quantity',
        'unit',
        'price_per_unit',
        'total_price',
    ];

    public function feedMedicinePurchase()
    {
        return $this->belongsTo(FeedMedicinePurchase::class, 'feed_medicine_purchase_id');
    }

}
