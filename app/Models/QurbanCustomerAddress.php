<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QurbanCustomerAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
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

    public function fullAddress()
    {
        return $this->address_line . ', ' .
            $this->region->village_name . ', ' .
            $this->region->district_name . ', ' .
            $this->region->regency_name . ', ' .
            $this->region->province_name . ', ' .
            $this->postal_code;
    }
}
