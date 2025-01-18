<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QurbanCustomerAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'qurban_customer_id',
        'description',
        'region_id',
        'postal_code',
        'address_line',
        'longitude',
        'latitude',
    ];

    public function qurbanCustomer()
    {
        return $this->belongsTo(QurbanCustomer::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }
}
