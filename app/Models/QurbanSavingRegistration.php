<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QurbanSavingRegistration extends Model
{
    use HasFactory;

    public function bankUsers()
    {
        return $this->belongsToMany(BankUser::class, 'qurban_saving_registration_user');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'qurban_saving_registration_user', 'qurban_saving_registration_id', 'bank_user_id');
    }
}
