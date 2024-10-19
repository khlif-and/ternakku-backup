<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use App\Enums\LivestockSexEnum;
use App\Models\ReproductionCycle;
use App\Enums\LivestockStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Enums\ReproductionCycleStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Livestock extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    public function livestockReceptionD()
    {
        return $this->belongsTo(LivestockReceptionD::class, 'livestock_reception_d_id');
    }

    public function livestockStatus()
    {
        return $this->belongsTo(LivestockStatus::class, 'livestock_status_id');
    }

    public function livestockType()
    {
        return $this->belongsTo(LivestockType::class, 'livestock_type_id');
    }

    public function livestockGroup()
    {
        return $this->belongsTo(LivestockGroup::class, 'livestock_group_id');
    }

    public function livestockBreed()
    {
        return $this->belongsTo(LivestockBreed::class, 'livestock_breed_id');
    }

    public function livestockSex()
    {
        return $this->belongsTo(LivestockSex::class, 'livestock_sex_id');
    }

    public function pen()
    {
        return $this->belongsTo(Pen::class, 'pen_id');
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
        $receivedAt->addYears($this->start_age_years);
        $receivedAt->addMonths($this->start_age_months);

        // Hitung selisih umur dari tanggal diterima hingga sekarang
        $ageInYears = $now->diffInYears($receivedAt);
        $ageInMonths = $now->diffInMonths($receivedAt) % 12;

        return "{$ageInYears} years and {$ageInMonths} months";
    }

    public function getCurrentPhotoAttribute()
    {
        return $this->photo ? getNeoObject( $this->photo) : null;
    }

    public function scopeOfType($query, $typeId)
    {
        return $query->where('livestock_type_id', $typeId);
    }

    public function scopeAlive($query)
    {
        return $query->where('livestock_status_id', LivestockStatusEnum::HIDUP->value);
    }

    public function scopeMale($query)
    {
        return $query->where('livestock_sex_id', LivestockSexEnum::JANTAN->value);
    }

    public function scopeFemale($query)
    {
        return $query->where('livestock_sex_id', LivestockSexEnum::BETINA->value);
    }

    public function expenses()
    {
        return $this->hasMany(LivestockExpense::class, 'livestock_id');
    }

    public function dof()
    {
        // Tanggal pertama
        $tanggal1 = Carbon::parse( $this->livestockReceptionD->livestockReceptionH->transaction_date );

        // Tanggal kedua
        $tanggal2 = Carbon::now();

        // Hitung jarak hari
        return $tanggal1->diffInDays($tanggal2);
    }

    public function insemination_number()
    {
        if($this->livestock_sex_id == LivestockSexEnum::BETINA->value){
            return ReproductionCycle::where('livestock_id' , $this->id)->count();
        }
        return null;
    }

    public function artificial_insemination_number()
    {
        if($this->livestock_sex_id == LivestockSexEnum::BETINA->value){
            return ReproductionCycle::where('livestock_id' , $this->id)->where('insemination_type' , 'artificial')->count();
        }
        return null;
    }

    public function natural_insemination_number()
    {
        if($this->livestock_sex_id == LivestockSexEnum::BETINA->value){
            return ReproductionCycle::where('livestock_id' , $this->id)->where('insemination_type' , 'natural')->count();
        }
        return null;
    }

    public function pregnant_number()
    {
        if($this->livestock_sex_id == LivestockSexEnum::BETINA->value){
            return ReproductionCycle::where('livestock_id' , $this->id)
                        ->whereIn('reproduction_cycle_status_id' , [
                                ReproductionCycleStatusEnum::PREGNANT->value,
                                ReproductionCycleStatusEnum::GAVE_BIRTH->value,
                                ReproductionCycleStatusEnum::BIRTH_FAILED->value,
                            ])
                        ->count();
        }
        return null;
    }

    public function children_number()
    {
        if($this->livestock_sex_id == LivestockSexEnum::BETINA->value){
            $count = 0;
            $reproductionCycleGaveBirth = ReproductionCycle::where('livestock_id' , $this->id)
                                            ->where('reproduction_cycle_status_id' , ReproductionCycleStatusEnum::GAVE_BIRTH->value)
                                            ->get();
            foreach($reproductionCycleGaveBirth as $item){
                $count += $item->livestockBirth->livestockBirthD->count();
            }

            return $count;
        }
        return null;
    }
}
