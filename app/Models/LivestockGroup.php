<?php

namespace App\Models;

use App\Enums\LivestockGroupEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LivestockGroup extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public static function getLivestockGroupId(LivestockGroupEnum $enum)
    {
        return self::where('name', $enum->value)->first()->id;
    }
}
