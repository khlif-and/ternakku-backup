<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Livestock extends Model
{
    use HasFactory;

    protected $fillable = [
        'livestock_reception_d_id',
        'is_qurban'
    ];

    // Relasi ke model lain jika ada
    public function livestockReceptionD()
    {
        return $this->belongsTo(LivestockReceptionD::class, 'livestock_reception_d_id');
    }
}
