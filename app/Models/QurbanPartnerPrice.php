<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QurbanPartnerPrice extends Model
{
    use HasFactory;

    public function scopeByWeight($query, $weight)
    {
        return $query->where('start_weight', '<=', $weight)
                     ->where('end_weight', '>=', $weight);
    }
}
