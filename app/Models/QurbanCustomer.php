<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QurbanCustomer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'farm_id',
        'created_by',
    ];

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function addresses()
    {
        return $this->hasMany(QurbanCustomerAddress::class);
    }

    public function scopeFilterMarketing($query, $farmId)
    {
        $cek = FarmUser::where('user_id', auth()->user()->id)
            ->where('farm_id', $farmId)
            ->whereIn('farm_role', ['OWNER', 'ADMIN'])
            ->get();

        if ($cek->isEmpty()) {
            return $query->where('created_by', auth()->user()->id);
        }

        return $query;
    }
}
