<?php

namespace App\Models;

use App\Models\Livestock;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Farm extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'registration_date', 'qurban_partner', 'owner_id'
    ];

    public function farmDetail()
    {
        return $this->hasOne(FarmDetail::class);
    }

    public function pens()
    {
        return $this->hasMany(Pen::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function scopeQurban(Builder $query): void
    {
        $query->where('qurban_partner', true);
    }

    public function livestockReceptionH()
    {
        return $this->hasMany(LivestockReceptionH::class);
    }

    public function livestocks()
    {
        return  $this->hasMany(Livestock::class);
    }

    public function getLivestockSummary($typeId)
    {
        $total = $this->livestocks()->alive()->ofType($typeId)->count();
        $male = $this->livestocks()->alive()->ofType($typeId)->male()->count();
        $female = $this->livestocks()->alive()->ofType($typeId)->female()->count();

        $qurbanCount = QurbanSaleLivestockD::whereIn('livestock_id', $this->livestocks()->ofType($typeId)->pluck('id'))->count();
        
        $salesOrder = $this->qurbanSalesOrder()->whereHas('qurbanSalesOrderD', function ($query) use ($typeId) {
            $query->where('livestock_type_id', $typeId);
        })->count();

        return [
            'total' => $total,
            'male' => $male,
            'female' => $female,
            'qurban_sale_count' => $qurbanCount,
            'sales_order_count' => $salesOrder,
        ];
    }

    public function getFullAddressAttribute()
    {
        $farmDetail = $this->farmDetail;
        if (!$farmDetail) {
            return null;
        }

        $addressParts = [
            $farmDetail->address_line,
            $farmDetail->region?->name,
        ];

        // Menghilangkan bagian alamat yang null
        $filteredAddressParts = array_filter($addressParts, fn($part) => !is_null($part));

        return implode(', ', $filteredAddressParts);
    }

    public function qurbanPrices()
    {
        return $this->hasMany(QurbanPartnerPrice::class, 'farm_id', 'id');
    }

    public function qurbanSalesOrder()
    {
        return $this->hasMany(QurbanSalesOrder::class, 'farm_id', 'id');
    }
}
