<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QurbanCustomer extends Model
{
    use HasFactory;

    protected $fillable = [
        'farm_id',
        'name',
        'phone_number',
    ];

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }
}
