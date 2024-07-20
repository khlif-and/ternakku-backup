<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function livestockStatus()
    {
        return $this->belongsTo(LivestockStatus::class, 'livestock_status_id');
    }

    public function scopeQurban(Builder $query): void
    {
        $query->where('is_qurban', true);
    }
}
