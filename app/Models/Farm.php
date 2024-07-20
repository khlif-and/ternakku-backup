<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Farm extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'registration_date', 'qurban_partner', 'owner_id'
    ];

    public function farmDetail()
    {
        return $this->hasOne(FarmDetail::class);
    }

    public function pens()
    {
        return $this->hasMany(Pen::class);
    }

    public function livestockBreeds()
    {
        return $this->hasMany(LivestockBreed::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function scopeQurban(Builder $query): void
    {
        $query->where('qurban_partner', true);
    }

}
