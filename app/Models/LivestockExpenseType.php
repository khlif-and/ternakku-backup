<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LivestockExpenseType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public $timestamps = false;

    public function livestockExpenses()
    {
        return $this->hasMany(LivestockExpense::class, 'livestock_expense_type_id');
    }
}
