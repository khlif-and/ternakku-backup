<?php

namespace App\Models;

use App\Models\Livestock;
use App\Models\LivestockStatus;
use App\Enums\LivestockStatusEnum;
use Illuminate\Database\Eloquent\Model;
use App\Exceptions\DuplicateEartagException;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LivestockReceptionD extends Model
{
    use HasFactory;

    protected $table = 'livestock_reception_d';

    protected $fillable = [
        'livestock_reception_h_id',
        'eartag_number',
        'rfid_number',
        'livestock_type_id',
        'livestock_group_id',
        'livestock_breed_id',
        'livestock_sex_id',
        'pen_id',
        'age_years',
        'age_months',
        'weight',
        'price_per_kg',
        'price_per_head',
        'notes',
        'photo',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if ($model->checkEartag()) {
                throw new DuplicateEartagException();
            }
        });

        static::created(function ($model) {
            $model->createLivestock();
        });

        static::updating(function ($model) {
            if ($model->checkEartag()) {
                throw new DuplicateEartagException();
            }
        });
    }

    public function checkEartag()
    {
        // Get the farm_id from the related LivestockReceptionH
        $farmId = $this->livestockReceptionH->farm_id;

        // Check if eartag_number already exists within the same farm_id and type_id
        return LivestockReceptionD::where('eartag_number', $this->eartag_number)
            ->whereHas('livestockReceptionH', function($query) use ($farmId) {
                $query->where('farm_id', $farmId);
            })
            ->where('livestock_type_id', $this->type_id)
            ->exists();
    }

    public function createLivestock()
    {
        Livestock::create([
            'livestock_reception_d_id' => $this->id,
            'livestock_status_id' => LivestockStatus::getLivestockStatusId(LivestockStatusEnum::HIDUP),
        ]);
    }

    public function livestockReceptionH()
    {
        return $this->belongsTo(LivestockReceptionH::class, 'livestock_reception_h_id');
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
        return $this->belongsTo(Pen::class, 'livestock_pen_id');
    }
}
