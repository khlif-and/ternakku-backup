<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use App\Enums\LivestockSexEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Livestock extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function livestockReceptionD()
    {
        return $this->belongsTo(LivestockReceptionD::class, 'livestock_reception_d_id');
    }

    public function livestockStatus()
    {
        return $this->belongsTo(LivestockStatus::class, 'livestock_status_id');
    }

    public function qurbanLivestock()
    {
        return $this->hasOne(QurbanLivestock::class);
    }

    public function scopeQurban($query)
    {
        return $query->whereHas('qurbanLivestock');
    }

    public function scopeNonQurban($query)
    {
        return $query->whereDoesntHave('qurbanLivestock');
    }

    public function getCurrentWeightAttribute()
    {
        return $this->livestockReceptionD->weight ?? null;
    }

    public function getCurrentAgeAttribute()
    {
        if (!$this->livestockReceptionD || !$this->livestockReceptionD->created_at) {
            return null;
        }

        // Ambil tanggal saat ini
        $now = Carbon::now();

        // Ambil tanggal saat ternak diterima
        $receivedAt = Carbon::parse($this->livestockReceptionD->created_at);

        // Tambahkan umur saat diterima
        $receivedAt->addYears($this->livestockReceptionD->age_years);
        $receivedAt->addMonths($this->livestockReceptionD->age_months);

        // Hitung selisih umur dari tanggal diterima hingga sekarang
        $ageInYears = $now->diffInYears($receivedAt);
        $ageInMonths = $now->diffInMonths($receivedAt) % 12;

        return "{$ageInYears} years and {$ageInMonths} months";
    }

    public function getCurrentPhotoAttribute()
    {
        return $this->livestockReceptionD->photo ?? null;
    }

    public function scopeOfType($query, $typeId)
    {
        return $query->whereHas('livestockReceptionD', function($q) use ($typeId) {
            $q->where('livestock_type_id', $typeId);
        });
    }

    public function scopeMale($query)
    {
        return $query->whereHas('livestockReceptionD', function($q) {
            $q->where('livestock_sex_id', LivestockSexEnum::JANTAN->value);
        });
    }

    public function scopeFemale($query)
    {
        return $query->whereHas('livestockReceptionD', function($q) {
            $q->where('livestock_sex_id', LivestockSexEnum::BETINA->value);
        });
    }
}
