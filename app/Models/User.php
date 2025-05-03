<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\RoleEnum;
use App\Models\FarmUser;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'password',
        'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Check if the user has a verified email.
     *
     * @return bool
     */
    public function hasVerifiedEmail()
    {
        return !is_null($this->email_verified_at);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_users');
    }

    public function banks()
    {
        return $this->belongsToMany(Bank::class, 'bank_user');
    }

    public function scopeVerified(Builder $query)
    {
        return $query->whereNotNull('email_verified_at');
    }

    public function isFarmer(): bool
    {
        return $this->roles->whereIn('id', [
            RoleEnum::FARMER->value,
        ])->isNotEmpty();
    }

    public function profile()
    {
        return $this->hasOne(Profile::class, 'user_id');
    }

    public function customer()
    {
        return $this->hasMany(QurbanCustomer::class, 'user_id');
    }

    public function farmDriver()
    {
        return FarmUser::where('farm_role' , 'DRIVER')
            ->where('user_id', $this->id)
            ->get();
    }

    public function farmOwner()
    {
        return FarmUser::where('farm_role' , 'OWNER')
            ->where('user_id', $this->id)
            ->get();
    }


    public function farmAdmin()
    {
        return FarmUser::where('farm_role' , 'ADMIN')
            ->where('user_id', $this->id)
            ->get();
    }

    public function farmAbk()
    {
        return FarmUser::where('farm_role' , 'ABK')
            ->where('user_id', $this->id)
            ->get();
    }

    public function farmMarketing()
    {
        return FarmUser::where('farm_role' , 'MARKETING')
            ->where('user_id', $this->id)
            ->get();
    }
}
