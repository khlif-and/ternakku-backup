<?php

namespace App\Models;

use App\Enums\RoleEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function users()
    {
        return $this->belongsToMany(User::class, 'role_users');
    }

    public static function getRoleId(RoleEnum $RoleEnum)
    {
        return self::where('name', $RoleEnum->value)->first()->id;
    }
}
