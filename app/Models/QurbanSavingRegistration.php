<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QurbanSavingRegistration extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    public function livestockBreed()
    {
        return $this->belongsTo(LivestockBreed::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function qurbanSavingRegistrationUser()
    {
        return $this->hasMany(QurbanSavingRegistrationUser::class);
    }

    public function getFullAddressAttribute()
    {
        $addressParts = [
            $this->address_line,
            $this->region?->name,
        ];

        // Menghilangkan bagian alamat yang null
        $filteredAddressParts = array_filter($addressParts, fn($part) => !is_null($part));

        return implode(', ', $filteredAddressParts);
    }
}
