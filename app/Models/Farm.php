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

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function scopeQurban(Builder $query): void
    {
        $query->where('qurban_partner', true);
    }

    public function getFullAddressAttribute()
    {
        $farmDetail = $this->farmDetail;
        if (!$farmDetail) {
            return null;
        }

        $addressParts = [
            $farmDetail->address_line,
            $farmDetail->village?->name,
            $farmDetail->district?->name,
            $farmDetail->regency?->name,
            $farmDetail->province?->name,
        ];

        // Menghilangkan bagian alamat yang null
        $filteredAddressParts = array_filter($addressParts, fn($part) => !is_null($part));

        return implode(', ', $filteredAddressParts);
    }

}
