<?php

namespace App\Models;

use App\Enums\LivestockTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LivestockType extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function breeds()
    {
        return $this->hasMany(LivestockBreed::class);
    }

    public static function getLivestockTypeId(LivestockTypeEnum $enum)
    {
        return self::where('name', $enum->value)->first()->id;
    }
}
