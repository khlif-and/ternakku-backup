<?php

namespace App\Models;

use App\Enums\LivestockStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LivestockStatus extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name'];

    public static function getLivestockStatusId(LivestockStatusEnum $status)
    {
        return self::where('name', $status->value)->first()->id;
    }
}
