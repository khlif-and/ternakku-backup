<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QurbanSavingRegistrationUser extends Model
{
    use HasFactory;

    protected $table = 'qurban_saving_registration_user';

    protected $guarded = [];

    protected function userBank()
    {
        return $this->belongsTo(UserBank::class);
    }
}
