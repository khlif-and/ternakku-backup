<?php

namespace App\Models;

use App\Enums\LivestockSexEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LivestockSex extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public static function getLivestockSexId(LivestockSexEnum $enum)
    {
        return self::where('name', $enum->value)->first()->id;
    }
}
