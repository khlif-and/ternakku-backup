<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
protected $fillable = [
    'id',
    'name',
    'province_id',
    'province_name',
    'regency_id',
    'regency_name',
    'district_id',
    'district_name',
    'village_id',
    'village_name',
];


    public $incrementing = false; // ✅ Tambahkan ini karena kita assign ID manual

    protected $keyType = 'string'; // ✅ Jika ID-nya kadang string (bukan integer murni)

    public function getFormattedNameAttribute()
    {
        return "{$this->name} - {$this->district_name}, {$this->regency_name}";
    }
}
