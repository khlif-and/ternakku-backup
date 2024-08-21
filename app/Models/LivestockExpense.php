<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LivestockExpense extends Model
{
    use HasFactory;

    protected $fillable = [
        'livestock_id',
        'livestock_expense_type_id',
        'amount',
    ];

    public function livestock()
    {
        return $this->belongsTo(Livestock::class);
    }

    public function livestockExpenseType()
    {
        return $this->belongsTo(LivestockExpenseType::class, 'livestock_expense_type_id');
    }

}
