<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LivestockBreed extends Model
{
    use HasFactory;

    protected $fillable = [
        'livestock_type_id',
        'name',
    ];

    public function livestockType()
    {
        return $this->belongsTo(LivestockType::class);
    }

    public function scopeQurban(Builder $query): void
    {
        $query->where('is_qurban', true);
    }
}
