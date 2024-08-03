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

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function regency()
    {
        return $this->belongsTo(Regency::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function village()
    {
        return $this->belongsTo(Village::class);
    }

    public function qurbanSavingRegistrationUser()
    {
        return $this->hasMany(QurbanSavingRegistrationUser::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'qurban_saving_registration_user', 'qurban_saving_registration_id', 'user_bank_id')
            ->withPivot('portion')
            ->withTimestamps();
    }
}
